<?php

$header_background_scroll_animation = true; //get_field('header_background_scroll_animation')
$header_headline_scroll_animation = false; // get_field('header_headline_scroll_animation')
$bg = get_trends_2020_bg();
?>

<style>
body:not(.ie) .trends-2020-hero:not(.is-loaded),
body:not(.ie) .trends-2020-hero__hero__img,
body:not(.ie) .trends-2020-hero__introduction {
  opacity:0;
}
</style>


<header class="trends-2020-hero" data-module-init="trends-2020-hero" data-add-scroll-animation="<?= $header_headline_scroll_animation ? 1:0 ?>">
  <div class="trends-2020-hero__bg <?= $header_background_scroll_animation ? '':'show-always'?>" style="background-image:url(<?=$bg['small']?>)" data-image-bg="<?=$bg['big']?>" data-module-init="image-load"></div>
  <div class="trends-2020-hero__wrap">
    <div class="trends-2020-hero__hero">
      <div class="trends-2020-hero__hero__padding"></div>
      <h1>
        <?php the_title(); ?>
      </h1>
      <img src="<?=get_template_directory_uri()?>/assets/img/Wellness.svg" class="trends-2020-hero__hero__img img-wellness">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--1">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--2">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--3">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--4">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--5">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--6">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--7">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--8">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--9">
      <img src="<?=get_template_directory_uri()?>/assets/img/Trends.svg" class="trends-2020-hero__hero__img img-trends img-trends--10">
      <img src="<?=get_template_directory_uri()?>/assets/img/2020.svg" class="trends-2020-hero__hero__img img-2020">
    </div>
    <div class="trends-2020-hero__introduction">
      <?= get_field('header_caption_text') ?>
    </div>
  </div>
</header>