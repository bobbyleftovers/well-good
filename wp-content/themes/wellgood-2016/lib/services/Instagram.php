<?php

namespace WG\Services;

use Instagram\Api;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class Instagram {

  protected $profile_handle = null;
  private $auth = null;

  function __construct($is_api = false){
    if($is_api){
      parent::__construct();
    } else {
      add_filter('acf/update_value/name=instagram_handle', array($this, 'on_update_instagram_handle'), 10, 3);
    }
  }

  function on_update_instagram_handle($value, $post_id, $field){
      $old_value = get_post_meta($post_id, $field['name'], true);
      if ($old_value != $value) {
        delete_transient( 'wg_instagram_feed_'.$old_value );
      }
      delete_transient( 'wg_instagram_feed_'.$value );
      return $value;
  }

  function get_profile_handle(){
    return $this->profile_handle;
  }

  function set_profile_handle($handle){
    $this->profile_handle = $handle;
  }

  function use_default_handle(){
    $handle = get_field('instagram_handle', 'option');
    $this->set_profile_handle($handle);
    return $handle;
  }

  function get_auth(){
    if(!$this->auth) {
      $auth = get_field('instagram_auth', 'option');
      if(!$auth || !$auth['username'] || !$auth['password']) throw new \Exception('No Auth Data provided');
      $this->auth = $auth;
    }
    return $this->auth;
  }

  function get_feed($handle = null){

    /* set handle */
    if($handle) $this->set_profile_handle( $handle );
    $handle = $this->use_default_handle();

    /* cached */
    $feed = get_transient( 'wg_instagram_feed_'.$handle );
    if($feed) return $feed;

    /* fetch new */
    try {
      $feed = $this->fetch_graph_api();
    } catch (\Exception $e) {
      $feed = $this->fetch_web_json();
    }

    /* save cache */
    set_transient( 'wg_instagram_feed_'.$handle, $feed, HOUR_IN_SECONDS);

    return $feed;
  }

  function fetch_graph_api(){

    $pool = new ArrayAdapter();
    $api = new Api($pool);
    $auth = $this->get_auth();
    $api->login($auth['username'], $auth['password']);

    $profile = $api->getProfile($this->get_profile_handle());
    $feed = array();
    foreach((array) $profile->getMedias() as $media){
      $feed[] = array(
        'type' => $media->getTypeName(),
        'link'=> $media->getLink(),
        'caption' => $media->getCaption(),
        'thumnbail' => $media->getThumbnailSrc(),
        'thumbnails' => $media->getThumbnails(),
        'source' => 'graph_api'
      );
    }
    return $feed;
  }

  function fetch_web_json(){
    $handle = curl_init();

    $url = 'https://www.instagram.com/'.$this->get_profile_handle().'/?__a=1';

    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);
    $output = curl_exec($handle);

    curl_close($handle);

    if(!$output) return null;

    $json = json_decode($output);

    $medias = array();

    foreach( $json->graphql->user->edge_owner_to_timeline_media->edges as $node ){
      $media = $node->node;
      $feed[] = array(
        'type' => $media->__typename,
        'link'=> 'https://www.instagram.com/p/'.$media->shortcode.'/',
        'caption' => $media->edge_media_to_caption->edges[0]->node->text,
        'thumnbail' => $media->thumbnail_src,
        'thumbnails' => $media->thumbnail_resources,
        'source' => 'web_json'
      );

    }

    return $feed;
  }

}
