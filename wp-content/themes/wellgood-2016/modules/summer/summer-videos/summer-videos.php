<?php
  $videos = $post->summer_videos_field;
  $videos_count = count($videos);
?>

<h2 class="summer-videos__title summer-subnav-headline text-h2">Related Videos</h2>

<div class="summer-videos videos--<?= $videos_count ?> desktop-enabled" data-module-init="flickity" data-group="yes" data-arrow="summer" data-contain="yes" data-dots="no" data-free="yes">
  <?php foreach ($videos as $key => $entry) :
      $post = get_post($entry['video']);
      setup_postdata($post);

      $video_id = $post->ID;
      $title = get_the_title($video_id);
      $thumbnail_url = get_the_post_thumbnail_url( $video_id, 'medium' );
      $permalink = get_the_permalink( $video_id );
    ?>
    <figure class="summer-video">
      <a href="<?= $permalink ?>">
        <div class="summer-video__image">
          <span class="summer-video__icon" aria-label="play video">
            <?= get_svg('video-icon', array(
              'role' => 'button'
            )); ?>
          </span>
          <?php the_module('image', $thumbnail_url); ?>
        </div>
      </a>
      <h4 class="summer-video__title mt-e15 text-h3"><?= $title ?></h4>
    </figure>
    <?php wp_reset_postdata(); ?>
  <?php endforeach; ?>
</div>
