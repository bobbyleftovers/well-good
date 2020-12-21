<?php

// Assets bundles
set_theme_template('main-2020');
set_theme_template('gutenberg');
set_theme_template('trends-2021');

// Header
if($is_parent) get_header('transparent');
else get_header();

// Layout
?>
<main class="wg__inline-ad-wrapper relative overflow-hidden theme-main-2020 trends-2021-layout
  <?= $is_parent ? 'trends-2021-layout--home mb-e40': 'trends-2021-layout--child mb-e40 md:mb-e0'?> 
  bg-body-background--<?=$post->ID?>
  ">
  <?php $render(); ?>
</main>

<?php
// CSS utilities
?>
<style>
  <?php foreach($colors as $postId => $postColors){
    foreach($postColors as $colorKey => $color){ ?>
        .bg-<?=$colorKey?>--<?=$postId?> { background-color: <?=$color?>; }
        .border-<?=$colorKey?>--<?=$postId?> { border-color: <?=$color?>; }
        .fill-<?=$colorKey?>--<?=$postId?> { fill: <?=$color?>; }
        .stroke-<?=$colorKey?>--<?=$postId?> { stroke: <?=$color?>; }
    <?php }
  } ?>

  :root {
    <?php
    foreach($colors[$post->ID] as $colorKey => $color){ ?>
      --trends-2021-<?=$colorKey?>: <?=$color?>;
    <?php } ?>
  }
</style>


<?php
// Footer
get_footer();

?>

<?php 
/* DON'T PURGE
last-block-trends-2021\/trends-2021-video
last-block-trends-2021\/trends-2021-spotlight
*/
?>