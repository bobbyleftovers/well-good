<div class="renew-year-2021-posts__active flex relative">
    <div class="flex-grow renew-year-2021-posts__pin pin overflow-hidden relative text-center text-white flex flex-col items-end justify-center p-e40 bg-<?=$post->ID?> h-viewport">
      <!-- image -->
      <img src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-posts/img/week-bk-1.png" class="renew-year-2021-posts__pin-accent renew-year-2021-posts__pin-accent--1 absolute" />
      <img src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-posts/img/week-bk-2.png" class="renew-year-2021-posts__pin-accent renew-year-2021-posts__pin-accent--2 absolute" /><?php
      if(isset($image)):
        $path_id = rand(1, 1000);
        $path_id = md5($path_id);?>

        <figure class="renew-year-2021-posts__figure absolute <?= $type === 'keyhole' ? 'w-4/5' : 'w-1/2' ?>">
          <svg class="svg-frame" viewBox="<?= $viewboxes[$type] ?>" fill="red" xmlns="http://www.w3.org/2000/svg">
            <clipPath id="frame-<?= $path_id ?>"  class="svg-frame__clip-path">
              <path fill-rule="evenodd" clip-rule="evenodd" d="<?= $clip_paths[$type] ?>" stroke="transparent"/>
            </clipPath>
            <image clip-path="url(#frame-<?= $path_id ?>)" class="svg-frame__img h-full w-full" xlink:href="<?= $image['url'] ?>" preserveAspectRatio="xMidYMin slice"></image>
          </svg>
        </figure><?php
      endif;?>

        <!-- text -->
      <div class="renew-year-2021-posts__text relative z-20 p-e45 xl:pr-0 w-1/2">
        <h2 class="page-title"><?= $title ?></h2>
        <div class="text-default"><?= $description ?></div>
      </div>
    </div>

    <!-- posts -->
    <div class="flex-grow relative renew-year-2021-posts__posts px-e50 ml:py-e60 <?= ($week_index === 1) ? 'renew-year-2021-posts__posts--first' : 'renew-year-2021-posts__posts--secondary' ?>"><?php
      if(isset($posts) && is_array($posts)) {
        $count = 0;
        foreach($posts as $post):
          $count++;?>
          <a href="<?= get_the_permalink($post->ID) ?>" class="renew-year-2021-posts__post mb-e50<?= ($count < count($posts)) ? ' border-b-2 border-grey' : null ?>">
            <div class="renew-year-2021-posts__post__img overflow-hidden relative mb-e35">
              <div class="renew-year-2021-posts__post__img-padding"></div><?php
                $image = get_the_post_thumbnail_url( $post, 'article') ?: wag_get_fallback_image()['url'];
                // Legacy component - contains lazy load on scroll
                the_module('image', array(
                  'image_src' => $image,
                  'image_src_retina' => $image,
                  'image_alt' => $post->post_title,
                  'is_video' => false
                )); ?>
              </div>
              <div class="renew-year-2021-posts__post__text">
                <h3 class="text-h3 mb-e12"><?= $post->post_title ?></h3>
                <div class="text-default mb-e30"><?= wp_trim_words($post->post_content, 15, '');?></div>
              </div>
          </a><?php
        endforeach;
        wp_reset_postdata();
      }?>
    </div>
  </div>