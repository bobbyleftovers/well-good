<?php
/**
 * Template Name: Product Guide
 * Horizontal scrolling template for product guides
 * @author BarrelNY
 */


$page_id = get_the_id();
?>

<?php get_header(); ?>
<?php the_module('advertisement', array(
		'class' => array('show-mobile'),
		'slots' => array(
			'adhesion'
		),
		'page' => 0,
		'iteration' => 0
	)); ?>

<div class="scroll-wrapper">
  <?php the_module('product-guide/product-guide-hero', $page_id); ?>
  <main class="product-guide-grid" data-module-init="product-guide-grid">
    <?php the_module( 'product-guide/product-guide-grid', $page_id); ?>
  </main>
</div>

<?php the_module('product-guide/product-guide-details'); ?>
<?php the_module('product-guide/product-guide-sidebar'); ?>
<?php the_module( 'product-guide/product-guide-quiz'); ?>

<?php the_module('product-guide/product-guide-loader'); ?>
<?php get_footer(); ?>
