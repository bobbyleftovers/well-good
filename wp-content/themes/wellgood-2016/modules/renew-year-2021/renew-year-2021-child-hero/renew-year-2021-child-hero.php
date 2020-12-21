<?php $image = get_the_post_thumbnail_url( $post, 'full') ?: wag_get_fallback_image()['url']; ?>

<div 
  class="renew-year-2021-child-hero flex relative overflow-hidden items-strech min-h-viewport flex-col ml:flex-row" 
  style="background-color: <?= $bg_colors[$post->ID] ?>"
  data-module-init="renew-year-2021-child-hero">

  <!-- text -->
  <div class="flex flex-col w-full ml:w-1/2 py-e40 px-e24 md:pt-e65 md:pl-e40 md:pr-e30 xl:pr-e27">
    <div class="renew-year-2021-child-hero__header min-h-32 relative flex flex-wrap justify-between items-center">
      <h1 class="text-white page-title mb-e10">
        <?= $post->post_title ?>
      </h1>
      <figure class="renew-year-2021-child-hero__logo my-0 mx-0 mb-e10"><img src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-child-hero/img/rny-child-hero-logo.png" alt="" /></figure>
      <span class="renew-year-2021-child-hero__bar absolute bottom-0 right-0 mb-e5"></span>
      <span class="renew-year-2021-child-hero__bar absolute bottom-0 right-0 mb-e10"></span>
    </div>

    <div class="renew-year-2021-child-hero__content flex flex-col justify-center items-end h-full mr-auto ml-auto"><?php
      if(get_field('sponsor_logo', $post->ID) && get_field('sponsor_logo', $post->ID)){
        $logo = get_field('sponsor_logo', $post->ID);
        $link = get_field('sponsor_link', $post->ID);
        $sponsor_copy = get_field('sponsor_copy', $post->ID) ? get_field('sponsor_copy', $post->ID) : '';
        echo $link ? '<a href="'.$link['url'].'" target="_blank" title="'.$link['title'].'" class="text-white w-full flex justify-center items-center mt-e43 ml:mb-e36 ml:mt-e30 fade-item">' : '<div class="text-white w-full flex justify-center items-center mt-e40 mb-e20 md:mt-e35 xl:mb-e10">';
        echo '<span class="flex justify-center items-center text-tag">'.$sponsor_copy;
        echo $logo ? '<img class="ml-e4" src="'.wg_resize($logo['url'], 80, 'auto', false).'" />' : '';
        echo '</span>';
        echo $link ? '</a>' : '</div>';
      }

      if(isset($hero_content)): ?>
        <div class="text-center text-big text-white mb-e22 md: mb-e27"><?= $hero_content ?></div>
      <?php endif; ?>
      <div class="renew-year-2021-child-hero__signup-form w-full">
        <?= brrl_the_module('main-2020/newsletter-form', ['newsletter_button_text' => 'Join The List']) ?>
      </div>
    </div>
  </div>

  <!-- image -->
  <div class="renew-year-2021-child-hero__featured-image overflow-hidden bg-cover bg-center bg-no-repeat flex-grow w-full ml:w-1/2 flex justify-end items-center relative renew-year-2021-child-hero__featured-image" style="background-image: url(<?=$image?>)">
    <svg class="renew-year-2021-child-hero__frame z-20 absolute top-0 w-full ml:relative" width="818" height="712" preserveAspectRatio="none" viewBox="0 0 818 712" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M795.001 285.228C795.001 147.61 673.942 -58.6516 425 36.0495C234.043 108.694 55.001 147.61 55.001 285.228C55.001 285.284 55.0039 285.341 55.0039 285.398H55.001V700H795.001V285.4H794.998C794.998 285.342 795.001 285.284 795.001 285.228Z" stroke="#F8FAF9" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
    <img class="accent accent--1 absolute z-10" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-child-hero/img/rny-child-hero-tr.png" />
    <img class="accent accent--2 absolute z-10" alt="" src="<?= get_stylesheet_directory_uri() ?>/modules/renew-year-2021/renew-year-2021-child-hero/img/rny-child-hero-br.png" />
  </div>
</div>