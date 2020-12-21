
<?php
$bg_image = get_template_directory_uri()."/assets/img/trends-2021-background-hero-lg-50-min.jpg"; 
$bg_image_xs = get_template_directory_uri()."/assets/img/trends-2021-background-hero-xs-50-min.jpg";
?>
<div class="trends-2021-home-hero text-white relative" data-module-init="trends-2021-home-hero" data-image-lg="<?=$bg_image?>"  data-image-xs="<?=$bg_image_xs?>">
  <div class="block">
    <div class="trends-2021-home-hero__bg-wrapper" style="opacity:0;">
      <canvas class="trends-2021-home-hero__bg-canvas"></canvas>
      <img class="block w-1/1 trends-2021-home-hero__bg-img" style="opacity:0">
      <div class="trends-2021-home-hero__bg-content-blend"></div>
    </div>
  </div>
  <div class="z-10 trends-2021-home-hero__content md:absolute left-0 w-1/1 flex items-center justify-center text-center">
    <div class="container flex-col flex items-center justify-center text-center">
      <h1 class="trends-2021-home-hero__title text-h1 text-white mb-e27 hidden" style="display:none;">
        <?= $post->post_title ?>
      </h1>
      <div class="trends-2021-home-hero__title-svg z-10" style="opacity: 0">
        <div class="block ml:hidden w-1/1 mb-e10"><?php include('title.sm.svg'); ?></div>
        <div class="_hidden ml:block w-1/1 mb-e25"><?php include('title.lg.svg'); ?></div>
      </div>
      <div class="trends-2021-home-hero__intro text-left sm:text-center" style="opacity: 0;">
        <?php if(isset($hero_content)): ?>
          <div class="text-big md:text-small ml:text-default"><?=strip_tags($hero_content, '<i><strong><b><a><em>')?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>