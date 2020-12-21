<?php


if($posts_by_term_and_keyword):

$injections = array();

if($newsletter_card['is_active']) {
  $newsletter_card['module'] = 'main-2020/newsletter-card';
  $injections[] = $newsletter_card;
}

if($text == '') { 
  $text = false; 
}

if(!isset($social_share)) $social_share = true;

?>

  <div class="container <?=$class?>">
    <!-- Text -->
    <div class="flex flex-wrap -mx-gutter1/2 <?= $text ? 'mb-e40 sm:mb-e30 lg:mb-e45':'mb-0' ?>">
      <div class="w-full ml:w-2/3 px-gutter1/2 text-gray">
        <h2 class="text-h2 relative mt-0 <?= $text ? 'mb-e6 md:mb-e10':'mb-e25 lg:mb-e30' ?>">
          <?= $title ?>
          <?= $anchor ?>
        </h2>
      <?php if($text): ?><div class="text-big"><?= $text ?></div><?php endif;?>
      </div>
    </div>


    <!-- Posts Grid -->
    <?php
    brrl_the_module('main-2020/posts-smart-grid', array(
        'posts' => $posts_by_term_and_keyword,
        'injections' => $injections,
        'social_share' => $social_share
      )); ?>

  </div>
<?php endif; ?>
