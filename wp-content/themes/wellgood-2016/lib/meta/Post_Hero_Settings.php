<?php
/**
 * Settings for post hero
 * 
 * Because some of the metadata for the hero has to be
 * fetched from APIs, the `_post_hero_data` meta field
 * has been created to house the post hero fields
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


namespace WG\Meta;

use WG\Content\Structured_Video_Data;

class Post_Hero_Settings {
  static $screens = array( 'post' );
  static $metadataKey = 'hero_video_data_layer';

  /**
   * Constructor
   */
  function __construct() {
    add_action( 'edit_post', array( $this, 'hero_settings_save_meta_box_data' ), 999, 1 );
    add_action( 'save_post', array( $this, 'hero_settings_save_meta_box_data' ), 999, 1 );
  }


  /* 
  *  Curl request to JW player API
  */
  function get_jw_player_meta( $media_id ) {
    $ch = curl_init("https://cdn.jwplayer.com/v2/media/$media_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result['playlist'][0];
  }

  function generate_duration( $duration_in_seconds ) {
    $formatted_duration = 'PT';
    $hours = floor( $duration_in_seconds / 3600 );
    $minutes = ( $duration_in_seconds / 60 ) % 60;
    $seconds = $duration_in_seconds % 60;
    
    if ( $hours ) :
      $formatted_duration .= "{$hours}H";
    endif;
    if ( $minutes ) :
      $formatted_duration .= "{$minutes}M";
    endif;
    if ( $seconds ) :
      $formatted_duration .= "{$seconds}S";
    endif;

    return $formatted_duration;
  }

  function update_video_data_layer( $post_id, $video_id ) {
    $data = Structured_Video_Data::get_video_data_layer( 'https://www.youtube.com/watch?v='.$video_id );

    update_post_meta( $post_id, self::$metadataKey, $data );
  }

  /**
   * When the post is saved, saves our custom data.
   *
   * @param int $post_id The ID of the post being saved.
   */
  function hero_settings_save_meta_box_data( $post_id ) {
    delete_post_meta( $post_id, self::$metadataKey );
    
    $template_data = array();

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
      return;
    endif;

    $template_name = get_field( 'post_hero_type', $post_id ) ?: 'image';
    switch ( $template_name ) :
      case 'legacy':
        $template_data['template_name'] = 'legacy';
        
        $thumbnail_id = get_post_thumbnail_id( $post_id );
        $thumbnails = get_posts( array(
          'post_type' => 'attachment',
          'include' => $thumbnail_id
        ) );
        $image_override = get_field( 'post_hero_featured_image_override', $post_id );
        $template_data['image'] = array(
          'image_src' => ! empty( $image_override) ? $image_override['sizes']['large'] : get_the_post_thumbnail_url( $post_id, 'large'),
          'image_src_retina' => ! empty( $image_override) ? $image_override['sizes']['large'] : get_the_post_thumbnail_url( $post_id, 'large'),
          'image_alt' => 'Thumbnail for ' . get_the_title( $post_id )
        );
  
        $caption = get_post_field( 'post_excerpt', $thumbnail_id );
        $caption_override = get_field( 'post_hero_featured_image_caption_override', $post_id );
        if ( ! empty( $caption ) ) : 
          $template_data['caption'] = $caption;
        endif;
        if ( ! empty( $image_override ) || ! empty( $caption_override ) ) :
          $override_image_caption = get_post_field( 'post_excerpt', $image_override['ID'] );
          $template_data['caption'] = $caption_override ?: $override_image_caption;
        endif;
        break;

      case 'image':
        $template_data['template_name'] = 'image';

        $template_data['hero_image_size'] = get_field( 'post_hero_image_size', $post_id ) ?: 'medium';

        $thumbnail_id = get_post_thumbnail_id( $post_id );
        $thumbnails = get_posts( array(
          'post_type' => 'attachment',
          'include' => $thumbnail_id
        ) );
        $image_override = get_field( 'post_hero_featured_image_override', $post_id );
        $template_data['image'] = array(
          'image_src' => ! empty( $image_override) ? $image_override['sizes']['large'] : get_the_post_thumbnail_url( $post_id, 'large'),
          'image_src_retina' => ! empty( $image_override) ? $image_override['sizes']['large'] : get_the_post_thumbnail_url( $post_id, 'large'),
          'image_alt' => 'Thumbnail for ' . get_the_title( $post_id )
        );
  
        $caption = get_post_field( 'post_excerpt', $thumbnail_id );
        $caption_override = get_field( 'post_hero_featured_image_caption_override', $post_id );
        if ( ! empty( $caption ) ) : 
          $template_data['caption'] = $caption;
        endif;
        if ( ! empty( $image_override ) || ! empty( $caption_override ) ) :
          $override_image_caption = get_post_field( 'post_excerpt', $image_override['ID'] );
          $template_data['caption'] = $caption_override ?: $override_image_caption;
        endif;
  
        break;

      case 'video':
        $template_data['template_name'] = 'video';

        $video_type = get_field( 'post_hero_video_type', $post_id );
        
        switch ( $video_type ) :
          case 'youtube':
            $video_link = get_field( 'post_hero_youtube_link', $post_id );
            $video_data = $video_link ? array(
              'player' => $video_type,
              'id' => get_youtube_video_id( $video_link )
            ) : FALSE;
            break;

          case 'jwplayer':
            $media_id = get_field( 'post_hero_jw_media_id', $post_id );
            $video_data = $media_id ? array(
              'player' => $video_type,
              'id' => $media_id
            ) : FALSE;

        endswitch;

        if ( ! $video_data || empty( $video_data ) || ! isset( $video_data ) ) :
          $template_data['template_name'] = 'legacy';
          break;
        endif;

        $override_thumbnail = get_field( 'post_hero_override_thumbnail', $post_id );
        $thumbnail = $override_thumbnail ? get_field( 'post_hero_video_thumbnail_override', $post_id ) : get_field( 'post_hero_video_thumbnail', $post_id );
        if ( ! $thumbnail ) :
          $thumbnail = get_field( 'featured_image_fallback', 'options' );
        endif;


        $template_data['video'] = array(
          'thumbnail' => $thumbnail['url']
        );

        if ( $video_data ) :
          switch ( $video_data['player'] ) :
            case 'youtube' :
              self::update_video_data_layer( $post_id, $video_data['id'] );

              $template_data['video']['player'] = $video_data['player'];
              $template_data['video']['id'] = $video_data['id'];
              break;

            case 'jwplayer' :
              $jw_player_player = get_field( 'post_hero_video_player', $post_id );
              $video_id = $video_data['id'];

              $template_data['video']['player'] = $video_data['player'];
              $template_data['video']['id'] = $video_id;
              $template_data['video']['player_id'] = $jw_player_player ?: get_field( 'jwplayer_default_player', 'options');

              $video_meta = self::get_jw_player_meta( $video_id );
              $template_data['video']['upload_date'] = date( 'D M d Y H:i:s e', $video_meta['pubdate'] );
              $template_data['video']['name'] = $video_meta['title'];
              $template_data['video']['duration'] = self::generate_duration( $video_meta['duration'] );
              $template_data['video']['thumbnail_url'] = $video_meta['image'];
              $template_data['video']['content_url'] = $video_meta['sources'][0]['file'];
              break;

          endswitch;
        endif;
        break;
      
      default:
        $template_data['template_name'] = 'image';

    endswitch;

    // update_post_meta( $post_id, '_post_hero_data', $template_data );
    $current_metadata = get_post_meta( $post_id, '_post_hero_data', true );
    if ($current_metadata) :
      delete_post_meta( $post_id, '_post_hero_data' );
    endif;
    add_post_meta( $post_id, '_post_hero_data', $template_data );
  }
}
