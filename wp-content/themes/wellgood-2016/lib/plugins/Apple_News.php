<?php

namespace WG\Plugins;

class Apple_News {

  private $skip_when_local = true;

  function __construct(){

    //add_filter( 'apple_news_publish_capability', array($this,'publish_capability'));
    add_filter( 'apple_news_skip_push', array($this,'skip_apple_news'), 10, 1 );
    add_filter( 'apple_news_generate_json', array($this,'add_hero_video'));
    add_filter( 'apple_news_generate_json', array($this,'filter_unwanted_components'));
    add_filter( 'apple_news_generate_json', array($this,'modify_advertisment_object') );
    add_filter( 'the_content', array($this,'add_related_content'), 10, 1 );
    add_filter( 'apple_news_exporter_post_thumb', array($this,'modify_thumbnail_baseurl') );
    add_filter('apple_news_get_json', array($this,'filter_json'));
  }


  function filter_json($json){

    if(is_production()) return $json;

    return str_replace(get_host(),'www.wellandgood.com', $json);

  }

  /*
  *  Allow to publish on cron
  */

  function publish_capability( $capability ) {

    if ( defined( 'DOING_CRON' ) && DOING_CRON ) return $capability;

  }

  /*
  *  Skip apple news on local environments
  */

  function skip_apple_news( $post_id ) {
    if (isset($_GET['skip_apple_news']) && $_GET['skip_apple_news']) :
      return true;
    endif;
    // Allow local if Channel ID is ...
    if (!is_production() && get_option('apple_news_settings')['api_channel'] === 'acce21cc-eea5-47c3-95ca-fe2731e28646') return false;
    return (
      ( get_option('apple_news_settings')['api_channel'] === 'aba3b525-bd1c-49b6-b505-1e64ec7cf280' && !is_production()) || is_local()
    );
  }

  /*
  *  Filter unwanted components
  */

  function filter_unwanted_components( $json ) {
    if ( ! empty( $json['components'] ) ) {
      $json['components'] = array_values( array_filter( $json['components'], function( $component ) {
        return ! in_array( $component['role'], array(
          /** add exemptions here */
        ) );
      } ) );
    }
    return $json;
  }


  /*
  *  Remove deprecated 'advertisingSettings' and add new supported object
  */

  function modify_advertisment_object(  $json ){

    //remove deprecated AdvertisingSettings
    unset($json['advertisingSettings']);

    // add AdvertisementAutoPlacement
    // https://developer.apple.com/documentation/apple_news/advertisementautoplacement
    $json['autoplacement'] = array(
        "advertisement" => array(
          "enabled" => true,
          "bannerType" => "any",
          "distanceFromMedia" => "10vh",
          "frequency" => 8,
          "layout" => array(
            "margin" => 10
          )
        )
      );
      return  $json;

  }


  /*
  *  Add related content links at the bottom of the post_content when apple news is exporting
  */

  function add_related_content( $content ) {
    if ( function_exists( 'apple_news_is_exporting' ) && apple_news_is_exporting() ) {
      return $content; //add related content heres
    }

    return $content;
  }

  function modify_thumbnail_baseurl ($imageUrl) {
    try {
      if (empty($imageUrl) || !$imageUrl) return $imageUrl;
      $url = parse_url($imageUrl);
      $url['scheme'] = isset($url["scheme"]) ? $url['scheme'] : "http";
      $url['host'] = get_host();
      return wg_unparse_url($url);
    } catch (\Throwable $th) {
      return $imageUrl;
    }
  }

  function add_hero_video ($json) {
    if (empty($json['components'])) return $json;

    $post_id = get_the_ID();
    $template_name = get_field( 'post_hero_type', $post_id );

    if ($template_name !== 'video') return $json;
    $video_type = get_field( 'post_hero_video_type', $post_id );
    $heroComponent = [];

    switch ( $video_type ) :
      case 'youtube':
        $video_link = get_field( 'post_hero_youtube_link', $post_id );
        $heroComponent = [
          "role" => 'embedwebvideo',
          "aspectRatio" => 1.777,
          'layout' => 'heroVideoLayout',
          "URL" => $video_link
        ];
        break;

      case 'jwplayer':
        $media_id = get_field( 'post_hero_jw_media_id', $post_id );
         $video_link = apply_filters('process_jw_player_post_hero', $media_id, true);
        if (empty( $video_link)) return $json;
        $heroComponent = [
          "role" => 'video',
          "aspectRatio" => 1.777,
          'layout' => 'heroVideoLayout',
          "URL" => $video_link
        ];
        break;
    endswitch;

    if (!empty($heroComponent)) {
      if ($json['components'][0]['role'] == 'header') {
        $json['components'][0]['components'] = [$heroComponent];
      } else {
        array_unshift($json['components'], [
          'role' => 'header',
          'layout' => 'heroVideoLayout',
          'components' => [$heroComponent]
        ]);
      }

      $json['componentLayouts']['heroVideoLayout'] = [
        "ignoreDocumentMargin" => true,
        "columnStart" => 0,
        "columnSpan" => 7
      ];
    }
    return $json;
  }

}
