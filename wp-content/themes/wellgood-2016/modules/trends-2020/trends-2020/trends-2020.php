<?php
global $trend_hub_ad_index;

$trend_hub_ad_index = 0;
$header_background_scroll_animation = true; //get_field('header_background_scroll_animation')

function get_trends_2020_bg(){
  $size_large = 'large';
  $size_small = 'thumbnail';
  $img = false; // get_field('header_bg');
  $fallbackBig = get_template_directory_uri()."/assets/img/trends-2020-hero-min.jpg";
  $fallbackSmall = get_template_directory_uri()."/assets/img/trends-2020-hero-min-sm.jpg";
  $imgBig = $img ? ($img['sizes'][$size_large] ? $img['sizes'][$size_large] : $img['sizes']['large']) : $fallbackBig;
  $imgSmall = $img ? ($img['sizes'][$size_small] ? $img['sizes'][$size_small] : $img['sizes']['thumbnail']) : $fallbackSmall;
  return array('small' => $imgSmall, 'big' => $imgBig);
}
?>

<!-- trends 2020 critical styles -->
<style>
  body.page-template-page-2020-hub .header {
    margin-bottom: 0 !important;
  }

  .trends-2020 {
    background: linear-gradient(-180deg, rgb(102, 14, 52) 0%, rgb(227, 104, 83) 100%);
    min-height: 100vh;
  }

  .trends-2020-hero__headline h1 {
    margin: 0 !important;
  }

  body:not(.ie) .trends-2020 .waypoint {
    opacity:0;
  }

</style>


<!-- trends 2020 main component -->
<main class="trends-2020 wg__inline-ad-wrapper" data-module-init="trends-2020">

  <?php the_module('trends-2020/trends-2020-hero'); ?>

  <div class="trends-2020__content" style="opacity:0;">
    <?php the_module('trends-2020/trends-2020-sections'); ?>

    <?php if ($header_background_scroll_animation){ 
      the_module('trends-2020/trends-2020-bg-texture'); 
    } ?>

    <!-- Social share buttons -->
    <?php the_module('trends-2020/trends-2020-social-share'); ?>

    <div class="clear" style="height: 1px; width:100%; display:block; clear:both; float:none;"></div>

  </div>

  <div class="clear" style="height: 1px; width:100%; display:block; clear:both; float:none;"></div>

</main>

