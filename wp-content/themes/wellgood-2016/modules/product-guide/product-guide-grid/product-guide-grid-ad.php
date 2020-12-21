<?php
/**
 * Serving the various types of sponsored content for the gift guide
 * @author BarrelNY
 *
 * @param index_phase - The largest cycle, includes 4 `index_group`
 * @param index_group - A group for each of the types of ads
 * @param index_item - Product index
 * @param ad_placement - int, determines whether the ad is on the top, middle or bottom
 * @param ad_index
 */

?>

<li class="product-guide-grid__item product-guide-grid__item--ad product-guide-grid--order-<?= $order; ?>">
  <?php the_module('advertisement', array(
		'class' => array('show-mobile'),
		'slots' => array(
			'horizontal',
			'slot'
		),
		'page' => 0,
		'iteration' => $ad_index
	)); ?>
</li>
