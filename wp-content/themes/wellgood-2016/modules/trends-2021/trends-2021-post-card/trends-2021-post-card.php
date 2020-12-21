<?php
$width = intval($width ?? 220); 
$height = intval($height ?? 135.454545455/100*$width);
$preload = $preload ?? false;
$post_card = get_field('post_card', $post->ID) ?: array('bottom_card_image' => false, 'top_card_image' => false );

$top_quality = $top_quality ?? 75;
$bottom_quality = $bottom_quality ?? 65;

$bottom_image = $post_card['bottom_card_image'] ?: get_the_post_thumbnail_url( $post, 'full') ?: wag_get_fallback_image()['url'];
$bottom_image = wg_resize( $bottom_image, $width, $height, true, $bottom_quality );

$top_image = $post_card['top_card_image'] ?: get_the_post_thumbnail_url( $post, 'full') ?: wag_get_fallback_image()['url'];
$top_image = wg_resize( $top_image, $width, $height, true, $top_quality  );

$format = $format ?? 'hero';
$path = 'M280.227 386.946H0.226562V125.094H0.377844C3.80714 55.4646 65.1078 0 140.227 0C215.345 0 276.646 55.4646 280.075 125.094H280.227V386.946Z';
$title_class = $title_class ?? 'mt-e15 md:mt-e18 ml:mt-e22 lg:mt-e33 text-h3 sm:text-h2 md:text-h3 ml:text-h2';
$add_scroll_parallax = $add_scroll_parallax ?? true;
$mouse_parallax_amount = $mouse_parallax_amount ?? 8;


if(!$top_image || !$bottom_image) return; ?>

<div 
  class="trends-2021-post-card trends-2021-post-card--<?= $format ?>" 
  data-module-init="trends-2021-post-card"
  data-scroll-parallax="<?= $add_scroll_parallax ? '1' : '0' ?>"
  data-mouse-parallax-amount="<?= $mouse_parallax_amount ?>"
  data-preload="<?= $preload ? 1 : 0 ?>"
  >
    <?= $format === 'grid' ? '<a href="'.get_the_permalink($post).'">' : null ?>
    <div class="trends-2021-post-card__wrapper">
      <!-- thumbnail -->
      <div class="trends-2021-post-card__inner z-10 relative">

        <div style="opacity:0" class="trends-2021-post-card__layer trends-2021-post-card--top trends-2021-post-card__layer--div">
          <div class="trends-2021-post-card__layer__padding">
          </div>
          <?php
            brrl_the_module('main-2020/base-image-placeholder', array(
              'width' => 220,
              'height' => 298
            ));?>
          <img data-src="<?=$top_image?>" width="220" height="298">
        </div>

        <?php
        // ensure clipping path id is unique
        $path_id = rand(5, 1000);
        $path_id = md5($path_id);?>

        <svg style="opacity:0" class="trends-2021-post-card__layer trends-2021-post-card--middle trends-2021-post-card--outline" viewBox="0 0 220 298" fill="none" data-speed-x="-10" data-speed-y="-10">
          <path style="opacity:0" fill="transparent" id="frame-<?= $path_id ?>" class="trends-2021-post-card__clip-path trends-2021-post-card__clip-path--middle stroke-primary--<?= $post->ID ?>" d="M0.880019 96.2809C3.56043 42.6892 51.4529 0 110.141 0C168.829 0 216.721 42.6892 219.402 96.2809H219.52V297.825H0.761778V101.128C0.761739 101.093 0.761719 101.057 0.761719 101.021C0.761719 100.986 0.761739 100.95 0.761778 100.915V96.2809H0.880019Z"/>
        </svg>


        <div style="opacity:0" class="trends-2021-post-card__layer trends-2021-post-card--bottom trends-2021-post-card__layer--div">
          <div class="trends-2021-post-card__layer__padding">
          </div>
          <?php
            brrl_the_module('main-2020/base-image-placeholder', array(
              'width' => 220,
              'height' => 298
            ));
          ?>
          <img data-src="<?=$bottom_image?>" width="220" height="298">
        </div>
        
        <?php
        if ($format === 'hero') {?>
          <div class="trends-2021-post-card__accent accent--bottom-left bg-primary--<?= $post->ID ?>"></div>
          <div class="trends-2021-post-card__accent accent--top-right bg-accent-1--<?= $post->ID ?>"></div><?php
        } else {?>
          <div class="trends-2021-post-card__accent accent--bottom-center bg-accent-1--<?= $post->ID ?>"></div><?php
        }?>
        <?php if ($format === 'grid'): ?><div class="trends-2021-post-card__accent accent--hover">
          <div class="absolute w-1/1 bg-primary--<?= $post->ID ?> left-0"></div>
        </div><?php endif; ?>
      </div>
      <!-- title -->
      <?= $format === 'grid' ? '<h2 class="trends-2021-post-card__title text-center z-20 relative '.$title_class.'">'.get_the_title($post).'</h2></a>' : null ?>
    </div>
</div>
