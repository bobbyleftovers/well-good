<?php
/**
 * Template Name: Fullscreen
 * Fullscreen page template
 * @author BarrelNY
 */


$page_id = get_the_id();
$display_title = get_field('fullscreen_page_display_title', $page_id);
$main_classes = array('page__main');
if (!$display_title) :
  array_push($main_classes, 'page__main--no-title');
endif;
?>

<?php get_header(); ?>

<?php the_module('advertisement', array(
  'class' => array(
    'show-mobile'
  ),
  'slots' => array(
    'horizontal',
    'adhesion'
  ),
  'page' => $page_id,
  'iteration' => 0
)); ?>

<article class="page">
  <?php if ($display_title === true) : ?>
    <div class="post__inner">
      <div class="container page__content-wrapper">
        <div class="page__content">
          <header class="page__header">
            <h1 class="big page__title"><?= wp_strip_all_tags(get_the_title()) ?></h1>
          </header>
        </div>
      </div>
    </div>
  <?php endif; ?>
  
  <main class="<?= implode(' ', $main_classes) ?>">
    <?php the_content(); ?>
  </main>

  <?php if ( get_field( 'show_instagram_feed' ) == 'yes' ): ?>
    <div class="container">
      <?php the_module('instagram-feed'); ?>
    </div>
  <?php endif; ?>
</article>

<?php get_footer(); ?>
