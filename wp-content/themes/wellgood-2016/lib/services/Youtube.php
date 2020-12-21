<?php

namespace WG\Services;

global $YT_Service;
$YT_Service = false;

class Youtube {

  private $youtubeApiKey = '';
  private $youtubeChannelId = '';

  /*
  *
  *  Actions and filters
  *
  */
  function __construct(){
    global $YT_Service;
    if($YT_Service) {
      $credentials = $YT_Service->getApiCredentials();
      $this->setApiCredentials($credentials['apiKey'], $credentials['channelId']);
    } else {
      $this->setApiCredentials();
      $YT_Service = $this;
    }
  }

  /**
   * Set API Credentials
   *
   * @return void
   */
  function setApiCredentials($youtubeApiKey = null, $youtubeChannelId = null) {
    $this->youtubeApiKey = $youtubeApiKey ?? get_field( 'youtube_api_key', 'options');
    $this->youtubeChannelId = $youtubeChannelId ?? get_field( 'youtube_channel_id', 'options');
    if(! $this->youtubeApiKey )  $this->youtubeApiKey ="AIzaSyBKaBPWFXf2Z9BiZl0Ux3ti3DZpqw_njiI";
    if(! $this->youtubeChannelId )  $this->youtubeChannelId ="UC1bcqvAnNsBoq_RWJNxYvhQ";
  }

  function getApiCredentials() {
    return array('apiKey'=>$this->youtubeApiKey, 'channelId' => $this->youtubeChannelId);
  }


  public function get_video_data( $url ) {
    $youtube_data = $this->fetch_youtube_video_data( $url );

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
      "contentUrl" => $this->get_youtube_watch_url($url),
      "interactionCount" => $youtube_data['statistics']['viewCount'],
      "thumbnails" => array(
        'default' => 'https://img.youtube.com/vi/'.$youtube_data['id'].'/default.jpg',
        'hqdefault' => 'https://img.youtube.com/vi/'.$youtube_data['id'].'/hqdefault.jpg',
        'mqdefault' => 'https://img.youtube.com/vi/'.$youtube_data['id'].'/mqdefault.jpg',
        'sddefault' => 'https://img.youtube.com/vi/'.$youtube_data['id'].'/sddefault.jpg',
        'maxresdefault' => 'https://img.youtube.com/vi/'.$youtube_data['id'].'/maxresdefault.jpg',
      )
    );

    return $data;
  }

  /**
   * Returns youtube video id from url
   * @param string - youtube embed/watch url
   * @return int - youtube ID
   */
  function get_youtube_id( $url ) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    $youtube_id = $match[1];

    return $youtube_id;
  }

  /**
   * Returns youtube video watch url from any url
   * @param string - youtube embed/watch url
   * @return string - youtube watch url
   */
  function get_youtube_watch_url( $url ) {
    return 'https://www.youtube.com/watch?v='.$this->get_youtube_id( $url );
  }

  /**
   * Fetches youtube metadata
   * @param string - youtube embed url
   * @return array - youtube video metadata
   */
  function fetch_youtube_video_data( $url ) {
    $data = array();
    $id = $this->get_youtube_id( $url );
    $data['id'] = $id;
    $youtube_api_url = "https://www.googleapis.com/youtube/v3/videos?id=".$id."&key=".$this->youtubeApiKey;
    $data_snippet = $this->fetch_youtube_video_data_part($youtube_api_url, 'snippet');
    $data_content_details = $this->fetch_youtube_video_data_part($youtube_api_url, 'contentDetails');
    //$data_statistics = $this->fetch_youtube_video_data_part($youtube_api_url, 'statistics');
    $data_statistics = array();

    return array_merge($data, $data_snippet, $data_content_details, $data_statistics);
  }

  /**
   * Fetches youtube data part
   * @param string - youtube api v3 url
   * @return array - youtube video metadata part
   */
  function fetch_youtube_video_data_part( $youtube_api_url, $part ) {
    $curl = curl_init( $youtube_api_url . "&part=" . $part );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    $return = curl_exec( $curl );
    curl_close( $curl );

    return json_decode( $return, true )['items'][0];
  }
}
