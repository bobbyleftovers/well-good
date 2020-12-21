<?php

namespace WG\Services;

class Brightcove {

  function __construct(){
    add_filter( 'brightcove_video_html',array($this,'inject_module') , 10, 5 );
  }

  function inject_module( $html, $type, $id, $account_id, $player_id ) {
    static $ran = false;
  
    $args = array(
      'html' => $html,
      'type' => $type,
      'id' => $id,
      'account_id' => $account_id,
      'player_id' => $player_id,
    );
  
    if(is_amp_endpoint()) {
      $html = get_module( 'brightcove-amp-video', $args);
    } else {
      $html = get_module( 'brightcove-video', $args);
    }
  
    if ( ! $ran && is_single() && is_main_query() ) {
      $html .= '<div class="advertisement__mobile-wrapper"></div>';
      $ran = true;
    }
  
    return $html;
  }

  static function request_brightcove_video($id) {

    $account_id = 4872551774001; // can we pull this from the database via the brightcove plugin?
    $policy_key = 'BCpkADawqM0xN_jYtCukVpquINqIuqAb6Xxv54nH0wtf5I_YwIoGhXKxfNiFfH4JVDWw4hypHtwMdzw28V-N4KOE_ihC7J_6UCAUVo4sFpZEI4lA7FtEAQILkdy1C1VXhCaernfpR7D2oGBd'; // can we pull this from the database via the brightcove plugin?

    $request_url = "https://edge.api.brightcove.com/playback/v1/accounts/$account_id/videos/$id";
    $ch = curl_init($request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json;pk=$policy_key"
    ));

    $ch_response = curl_exec($ch);

    if($ch_response === false){
      $info = curl_getinfo($ch);
      curl_close($ch);
      die('Error during curl exec. Info: ' . var_export($info));
    }

    curl_close($ch);
    $data = json_decode($ch_response);
    if (isset($data->response->status) && $data->response->status == 'ERROR') {
      die('error occured: ' . $data->response->errormessage);
    }

    return $data;
  }

  /**
   * Get the posts tagged video from WordPress from within a certain timeframe
   * Get the IDs of the videos within post_content
   * Add those IDs to an array and return the array for comparing to the Brightcove videos
   * @return array $ids - array of Brightcove video IDs in the posts tagged video
   */

  static function get_video_ids($time_since = null) {

    $args = array(
      'tag' => 'video',
      'numberposts' => 50,
      'post_type' => array('post', 'recipe'),
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC'
    );

    if ($time_since) {
      $args['date_query'] = "$time_since ago";
    }

    $posts = get_posts($args);
    $pattern = '/video_id="(.*?)"/';
    $ids = [];

    foreach ($posts as $post) {
      $ids_found = get_pattern_match($pattern, $post->post_content);

      foreach ($ids_found as $id) {
        array_push($ids, $id);
      }
    }

    return $ids;
  }

  /**
   * Build array of Brightcove videos from the array of video IDs from posts
   * @param string $time_since - amount of time to retrieve posts after e.g. '1 day ago'
   * @return array $videos - array of Brightcove videos
   */

  static function get_brightcove_videos($time_since) {
    $videos = [];
    $video_ids = self::get_video_ids($time_since);

    foreach ($video_ids as $id) {
      $video = self::request_brightcove_video($id);
      array_push($videos, $video);
    }

    return $videos;
  }

}

