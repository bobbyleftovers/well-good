<?php 
//alignment
global $home_post_card_align;
if(!isset($home_post_card_align) || $home_post_card_align == 'right'): 
  $home_post_card_align = 'left';
else: 
  $home_post_card_align = 'right';
endif;

$align = $home_post_card_align;
$container_classes = $align === 'left' ? 'pl-e22 md:pl-0' : 'pr-e22 md:pr-0';
$content_classes = $align === 'left' ? 'md:pl-e40 ml:pl-e90 xl:pl-e85 items-start' : 'md:pr-e40 ml:pr-e90 xl:pr-e85 items-end';
$p_classes = $align === 'left' ? 'pr-e30 ml:pr-e80 xl:pr-e150 text-left' : 'pl-e30 ml:pl-e80 xl:pl-e150 text-right';
$image = get_the_post_thumbnail_url( $post, 'full') ?: wag_get_fallback_image()['url'];
$image = wg_resize($image, 880, 480, true);
if(!isset($bg_colors[$post->ID])) $bg_colors[$post->ID] = get_field('bg_color', $post->ID);
$intro = (get_field('card_intro', $post->ID)) ? get_field('card_intro', $post->ID) : false;
?>
<div
  class="renew-year-2021-home-post-card renew-year-2021-home-post-card--<?=$align?> flex items-strech flex-col md:flex-row pt-e70 pb-e22 md:py-0 <?= $container_classes ?>" 
  data-module-init="renew-year-2021-home-post-card"
  style="background-color: <?=$bg_colors[$post->ID]?>"
  >
  <div class="renew-year-2021-home-post-card__content <?= $align == 'left' ? 'md:order-2':'md:order-1' ?> flex flex-col text-white flex md:justify-center overflow-hidden <?= $content_classes ?>">
    <h2 class="text-white page-title"><?= $post->post_title ?></h2>
    <span class="renew-year-2021-home-post-card__bar mb-e5"></span>
    <span class="renew-year-2021-home-post-card__bar mb-e12 md:mb-e15 ml:mb-e21 xl:mb-e28"></span>
    <?= $intro ? '<p class="text-white mt-e0 '.$p_classes.'">'.$intro.'</p>' : ''?>
    <a class="text-link base-button base-button--white mt-e21 md:mt-e16 ml:mt-e17 mb-e24 md:mb-0 relative" href="<?= get_the_permalink($post->ID) ?>"><span class="relative z-20">Get Started</span></a>
  </div>

  <div class="renew-year-2021-home-post-card__img bg-cover bg-top-center bg-no-repeat flex-grow <?=$align == 'left' ? 'md:order-1':'md:order-2'?>" 
      style="background-image: url(<?= $image ?>)">
  </div>
</div>
