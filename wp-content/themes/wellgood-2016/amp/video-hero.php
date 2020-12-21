<?php
  $post_id = get_the_ID();
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

  $override_thumbnail = get_field( 'post_hero_override_thumbnail', $post_id );
  $thumbnail = $override_thumbnail ? get_field( 'post_hero_video_thumbnail_override', $post_id ) : get_field( 'post_hero_video_thumbnail', $post_id );
  if ( ! $thumbnail ) :
    $thumbnail = get_field( 'featured_image_fallback', 'options' );
  endif;
?>
<?php if ($video_type === 'youtube'): ?>
  <amp-youtube
    data-videoid="<?= $video_data['id'] ?>"
    layout="responsive"
    width="480"
    height="270"
    layout="responsive"
  >
    <amp-img
      src="<?= $thumbnail['url']; ?>"
      placeholder
      layout="fill"
    />
  </amp-youtube>

<?php endif; ?>

<?php if ($video_type === 'jwplayer'): ?>
  <amp-jwplayer
    data-player-id="<?= $video_data['player'];?>"
    data-media-id="<?= $video_data['id'];?>"
    layout="responsive"
    width="16"
    height="9"
  >
  </amp-jwplayer>
<?php endif; ?>
