<?php
  $hero_intro = (isset($hero_content['hero_intro']) && $hero_content['hero_intro'] != '') ? $hero_content['hero_intro'] : get_the_excerpt(); 
  if($hero_intro == '') $hero_intro = false;
  $sponsor_name = (isset($hero_content['sponsor']['sponsor_name']) && $hero_content['sponsor']['sponsor_name'] != '') ? $hero_content['sponsor']['sponsor_name'] : false; 
  $sponsor_logo = $hero_content['sponsor']['logo'];
  $sponsor_url = $hero_content['sponsor']['url'];
  $has_sponsor = $sponsor_name || $sponsor_logo;
?>

<div class="trends-2021-child-hero overflow-hidden flex items-center <?=$hero_color?> bg-secondary--<?=$post->ID?>">
  <div class="w-1/1 pt-e40 pb-e20 sm:pt-e60 md:py-e40">
    <div class="flex-col md:flex-row container trends-2021-child-hero__container flex-grow flex items-center justify-between">
      <!-- content -->
      <div class="trends-2021-hero__content flex-grow justify-start align-center text-center sm:pt-e20 ml:pt-e45 lg:pt-e45 order-2 md:order-1 z-20">
        <h1 class="text-h3 ml:text-h1 trends-2021-hero__title text-center"><?= $post->post_title ?></h1>
        <?= $hero_intro ? '<div class="text-center text-default md:text-small ml:text-default my-e0 pt-e7 pb-e9 sm:pb-e5 sm:pt-e10 ml:pt-e4 ml:pb-e8 lg:pt-e12 lg:pb-e14">'.$hero_intro.'</div>' : null ?>

        <?php if($has_sponsor): ?>
          <!-- sponsor -->
          <p class="text-default my-e0 trends-2021-hero__sponsor flex items-center justify-center"><span class="text-link">Sponsored by</span>
            <span class="ml-e4 inline-flex">
              <?php if ($sponsor_url): ?><a target="_blank" class="items-center" href="<?=$sponsor_url?>"><?php endif; ?>
              <?php if($sponsor_logo): ?>
                  <img class="inline-block mb-e1" src="<?=$sponsor_logo['url']?>" width="<?=$sponsor_logo['width']?>" height="<?=$sponsor_logo['height']?>">
              <?php else: ?>
                  <span class="inline-flex pt-e2"><?= $sponsor_name ?></span>
              <?php endif; ?>
              <?php if ($sponsor_url): ?></a><?php endif; ?>
            </span>
          </p>
        <?php endif; ?>

        <!-- TMP: social links taken from footer -->
        <div class="menu-social-menu-container">
          <?php the_module('social-media-links', array(
            'menu_class' => 'flex justify-center items-center trends-2021-child-hero__social',
            'label_class' => 'text-gray-dark'
          )); ?>
        </div>
      </div>

      <!-- post-card -->
      <div class="justify-end trends-2021-hero__card-wrap flex-grow flex-shrink-0 mb-e33 sm:mb-e0 order-1 md:order-2 z-10"><?php
        brrl_the_module('trends-2021/trends-2021-post-card', [
          'post' => $post,
          'width' => 350,
          'format' => 'hero'
        ]);?>
      </div>
    </div>
  </div>
</div>