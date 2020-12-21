<?php

namespace WG\Services;

use WG\Meta\Post_Hero_Settings;

class YoutubeThumbnailFetch {
  static $youtubeApiKey = '';
  static $youtubeChannelId = '';

  function __construct () {
    $this->setApiCredentials();

    add_action( 'save_post', array( $this, 'setVideoThumbnail' ), 10, 2);
  }

  function setApiCredentials() {
    self::$youtubeApiKey = get_field( 'youtube_api_key', 'options');
    self::$youtubeChannelId = get_field( 'youtube_channel_id', 'options');
  }

  function setVideoThumbnail( $post_id ) {
    $hero_type = get_field( 'post_hero_type', $post_id );
    $video_link = get_field( 'post_hero_youtube_link', $post_id );
    $video_type = get_field( 'post_hero_video_type', $post_id );
    $thumbnail = get_field( 'post_hero_video_thumbnail', $post_id );
    if ( ( $hero_type !== 'video' || $video_type !== 'youtube' || empty( $video_link ) ) || !empty( $thumbnail ) ) :
      return;
    endif;

    $upload_dir = wp_upload_dir();
    $youtube_id = get_youtube_video_id( $video_link );
    $filename = "video_hero_thumbnail-{$post_id}-{$youtube_id}.jpg";
    $data = file_get_contents('https://www.googleapis.com/youtube/v3/videos?key=' . self::$youtubeApiKey . '&part=snippet&id=' . $youtube_id );
    $json = json_decode($data);
    $thumbnail_url = $json->items[0]->snippet->thumbnails->maxres->url;
    $uploadfile = $upload_dir['path'] . '/' . $filename;

    $contents= file_get_contents( $thumbnail_url );
    $savefile = fopen( $uploadfile, 'w' );
    fwrite( $savefile, $contents );
    fclose( $savefile);

    $wp_filetype = wp_check_filetype( basename( $filename ), null );

    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $filename,
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $uploadfile );

    update_field( 'post_hero_video_thumbnail', $attach_id, $post_id );
  }
}
