<?php
global $post;?>

<div class="renew-year-2021-home-hero min-h-viewport flex items-center justify-center bg-tan-light relative" data-module-init="renew-year-2021-home-hero">
  <div class="stack stack--top-left absolute top-0 left-0">
    <img class="animated-item stack__item stack__item--1 top-0 left-0 absolute z-20" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-bk-1.png" />
    <img class="animated-item stack__item stack__item--2 bottom-e10 absolute z-40" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-path-1.svg" />
    <img class="animated-item stack__item stack__item--3 bottom-0 absolute z-20" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-img-1.png" />
    <img class="animated-item stack__item stack__item--4 bottom-0 absolute z-10" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-bk-2.png" />
  </div>

  <div class="stack stack--top-right absolute top-0 right-0">
    <img class="animated-item stack__item stack__item--1 top-0 right-0 ml:right-e24 ml:top-e33 xl:right-e60 absolute z-40" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-path-2.svg" />
    <img class="animated-item stack__item stack__item--2 top-0 right-0 ml:right-e13 xl:right-e50 absolute z-30" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-img-2.png" />
    <img class="animated-item stack__item stack__item--3 top-0 right-0 absolute xl:right-e10 z-20" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-bk-3.png" />
    <img class="animated-item stack__item stack__item--4 top-0 right-0 absolute z-10" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/rny-home-hero-bk-4.png" />
  </div>

  <div class="renew-year-2021-home-hero__copy container text-center text-gray relative z-50 px-e15">
    <h1>
      <span class="hidden"><?= $post->post_title; ?></span>
      <img class="fade-item" alt="<?= $post->post_title; ?>" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-home-hero/img/renew-year-logo.png" />
    </h1><?php
    if(get_field('sponsor_logo', $post->ID) && get_field('sponsor_logo', $post->ID)){
      $logo = get_field('sponsor_logo', $post->ID);
      $link = get_field('sponsor_link', $post->ID);
      $sponsor_copy = get_field('sponsor_copy', $post->ID) ? get_field('sponsor_copy', $post->ID) : '';
      echo $link ? '<a href="'.$link['url'].'" target="_blank" title="'.$link['title'].'" class="w-full flex justify-center items-center my-e24 ml:mb-e36 ml:mt-e30 fade-item">' : '<div class="w-full flex justify-center items-center my-e24 ml:mb-e36 ml:mt-e30 fade-item">';
      echo '<span class="flex justify-center items-center text-tag">'.$sponsor_copy;
      echo $logo ? '<img class="ml-e4" src="'.wg_resize($logo['url'], 80, 'auto', false).'" />' : '';
      echo '</span>';
      echo $link ? '</a>' : '</div>';
    }?>
    <?php if(isset($hero_content)): ?>
      <p class="text-center fade-item"><?= $hero_content; ?></p>
    <?php endif; ?>
  </div>
</div>