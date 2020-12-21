<?php
/**
 * Structured Video Data
 *
 * Get YouTube video ID from any post that includes
 * a YouTube video in `the_content`
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */

namespace WG\Content;

use WG\Meta\Post_Hero_Settings;

class Structured_Video_Data {
  static $youtubeApiKey = '';
  static $youtubeChannelId = '';
  static $metadataKey = 'video_data_layer';
  private static $post = null;
  static $youtube_service = null;

  /*
  *
  *  Actions and filters
  *
  */
  function __construct(){
    $this->setApiCredentials();

    add_action( 'save_post_dom', array($this, 'parse_youtube_iframes_post_dom'), 10, 2);
    add_filter( 'gtm_data_layer', array($this, 'add_video_data_layer'));
    add_action( 'post-content_before', array($this, 'add_video_data_to_header_script'), 10, 2);
  }

  /**
   * Set API Credentials
   *
   * @return void
   */
  function setApiCredentials() {
    self::$youtubeApiKey = get_field( 'youtube_api_key', 'options');
    self::$youtubeChannelId = get_field( 'youtube_channel_id', 'options');
  }

  /**
   * Action: save_post
   * Parses Post DOM
   */
  public static function parse_youtube_iframes_post_dom( $dom, $post ) {
    self::$post = $post;
    self::clear_video_data_layer_post_meta();

    $embedded_youtube_videos = array();
    $iframes = $dom->find('iframe');
    $youtube_regex = '~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?[^\s]+~';
    preg_match($youtube_regex, $dom->outerHtml, $youtube_urls);
    if ( sizeof($iframes) == 0 && sizeof($youtube_urls) == 0 ) :
      return;
    endif;
    self::setApiCredentials();

    foreach( $iframes as $iframe ) :
      if ( strpos( $iframe->src, 'youtube' ) !== false ) :
        $id = get_youtube_video_id( $iframe->src );
        if ( array_key_exists( $id, $embedded_youtube_videos ) ) :
          continue;
        endif;
        $embedded_youtube_videos[$id] = $iframe->src;
      endif;
    endforeach;
    foreach( $youtube_urls as $youtube_url ) :
      if ( ! in_array( $youtube_url, $embedded_youtube_videos ) ) :
        $id = get_youtube_video_id( $youtube_url );
        if ( array_key_exists( $id, $embedded_youtube_videos ) ) :
          continue;
        endif;
        $embedded_youtube_videos[$id] = $youtube_url;
      endif;
    endforeach;

    foreach( $embedded_youtube_videos as $id => $video ) :
      self::save_video_data_layer($video);
    endforeach;
  }

  public static function get_video_data_layer( $url ) {
    $youtube_data = self::fetch_youtube_video_data( $url );
    if ( ! $youtube_data ) :
      return;
    endif;

    $data = array(
      "@context" => "http://schema.org",
      "@type" => "VideoObject",
      "name" => $youtube_data['snippet']['title'],
      "description" => $youtube_data['snippet']['description'],
      "uploadDate" => $youtube_data['snippet']['publishedAt'],
      "embedUrl" => $url,
      "thumbnailUrl" => $youtube_data['snippet']['thumbnails']['default']['url'],
      "duration" => $youtube_data['contentDetails']['duration'],
      "contentUrl" => self::get_youtube_watch_url($url),
      "interactionCount" => $youtube_data['statistics']['viewCount'],
    );

    return $data;
  }

  /**
   * Gets & stores video data using video url
   * @param string $url from video embed/iframe
   */
  public static function save_video_data_layer( $url ) {
    $data = self::get_video_data_layer( $url );

    self::update_video_data_layer_post_meta( $data );

    return;
  }

  /**
   * Updates post metadata for video data layer
   * @param array - data layer for a single video
   */
  public static function update_video_data_layer_post_meta( $video_data_layer ) {
    $data = get_post_meta( self::$post->ID, self::$metadataKey, true );
    if ( ! is_array( $data ) ) :
      $data = array();
    endif;

    $exists = false;
    foreach ( $data as $key => $video ) :
      if ( $video['embedUrl'] === $video_data_layer['embedUrl'] ) :
        $data[$key] = $video_data_layer;
        $exists = true;
        break;
      endif;
    endforeach;

    if ( ! $exists ) :
      array_push($data, $video_data_layer);
    endif;
    update_post_meta(self::$post->ID, self::$metadataKey, $data);
  }

  /**
   * Clear post metadata for video data layer
   * @param array - data layer for a single video
   */
  public static function clear_video_data_layer_post_meta() {
    delete_post_meta(self::$post->ID, self::$metadataKey);
  }

  /**
   * Filter: gtm_data_layer
   * Fetches stored video metadata and adds it to dataLayer
   * @param array $data - previous dataLayer data
   * @return array $data - dataLayer data with video data added
   */
  public static function add_video_data_layer($data){
    global $post;
    if(!$post || !is_object($post)) return;
    if($post->post_type !== 'post' && $post->post_type !== 'page') return $data;

    $post_hero_video = get_post_meta( $post->ID, Post_Hero_Settings::$metadataKey, false ) ?: array();
    $in_article_videos = get_post_meta( $post->ID, self::$metadataKey, true ) ?: array();

    $data['youtube_videos'] = array_merge( $post_hero_video, $in_article_videos );
    return $data;
  }

  /**
   * Action: post-content_before
   * Fetches stored video metadata and adds it to header script
   * @param array $data - previous dataLayer data
   * @return array $data - dataLayer data with video data added
   */
  public static function add_video_data_to_header_script() {
    global $post;
    if (
      !$post ||
      !is_object($post) ||
      !property_exists($post, 'post_type') ||
      ($post->post_type !== 'post' && $post->post_type !== 'page')
      ) :
      return;
    endif;

    $youtube_videos = get_post_meta( $post->ID, self::$metadataKey, true );
    if ( ! is_array( $youtube_videos ) || sizeof( $youtube_videos ) == 0 ) :
      return;
    endif;

    $video = $youtube_videos[0];
    if( $video ):
    echo '<script type="application/ld+json">';
    echo json_encode($video);
    echo '</script>';
    endif;
  }

  /**
   * Returns youtube video id from url
   * @param string - youtube embed/watch url
   * @return int - youtube ID
   */
  static function get_youtube_id( $url ) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    $youtube_id = $match[1];

    return $youtube_id;
  }

  /**
   * Returns youtube video watch url from any url
   * @param string - youtube embed/watch url
   * @return string - youtube watch url
   */
  static function get_youtube_watch_url( $url ) {
    return 'https://www.youtube.com/watch?v='.self::get_youtube_id( $url );
  }

  /**
   * Fetches youtube metadata
   * @param string - youtube embed url
   * @return array - youtube video metadata
   */
  static function fetch_youtube_video_data( $url ) {
    $id = self::get_youtube_id( $url );
    $youtube_api_url = "https://www.googleapis.com/youtube/v3/videos?id=".$id."&key=".self::$youtubeApiKey;
    $data_snippet = self::fetch_youtube_video_data_part($youtube_api_url, 'snippet');
    if ( $data_snippet['snippet']['channelId'] !== self::$youtubeChannelId ) :
      return false;
    endif;

    $data_content_details = self::fetch_youtube_video_data_part($youtube_api_url, 'contentDetails');
    $data_statistics = self::fetch_youtube_video_data_part($youtube_api_url, 'statistics');

    return array_merge($data_snippet, $data_content_details, $data_statistics);
  }

  /**
   * Fetches youtube data part
   * @param string - youtube api v3 url
   * @return array - youtube video metadata part
   */
  static function fetch_youtube_video_data_part( $youtube_api_url, $part ) {
    $curl = curl_init( $youtube_api_url . "&part=" . $part );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    $return = curl_exec( $curl );
    curl_close( $curl );

    return json_decode( $return, true )['items'][0];
  }
}
