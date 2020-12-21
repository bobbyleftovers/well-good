<?php
/**
 * Product guide grid
 * @author BarrelNY
 */

global $post;

$page_id = $post->product_guide_grid_field ? $post->product_guide_grid_field : get_the_id();
$parent_id = get_the_parent_id($page_id);
$is_index = $page_id == $parent_id;
$taxonomy = 'product_categories';
$ad_index = 0;
/**
 * Post information
 */
$campaign_id = get_field('product_guide_campaign', $parent_id);
$no_product_copy = get_field('product_guide_no_products', $parent_id);

$index_sponsor = array(
  'ad_module',
  'product_module',
  'flex_module',
  'flex_module'
);

$products_args = array(
  'posts_per_page'  => -1,
  'post_type'       => 'products',
	'tax_query'       => array( array(
      'taxonomy'      => 'product_campaigns',
      'field'         => 'term_id',
      'terms'         => $campaign_id,
      'operator'      => 'IN'
  ))
);
/**
 * Variables: Index page
 */
if ($is_index) :
  $category = null;
  $sponsored_content_items = get_field('product_guide_sponsored_content', $parent_id);

/**
 * Variables: Category pages
 */
else :
  $sponsored_content_items = get_field('product_guide_category_sponsored_content', $page_id);
  $category = get_field('product_guide_product_category', $page_id);
	$category_args = array(
		'taxonomy'      => $taxonomy,
		'field'         => 'term_id',
		'terms'         => $category,
		'operator'      => 'IN'
	);
  array_push( $products_args['tax_query'], $category_args );
endif;

/**
 * REMOVE SPONSORED CONTENT FROM LOOP
 * Find all Sponsored products and remove
 * them from the loop, they will be injected
 * into the loop seperately
 */
if ($sponsored_content_items) :
  $products_args['post__not_in'] = array();
  foreach($sponsored_content_items as $sponsored_content_item) :
    foreach($sponsored_content_item as $key => $item) :
      if (!empty($item['product_object'])) :
        $object = $item['product_object'];
        $sponsored_product_id = $object->ID;
        array_push($products_args['post__not_in'], $sponsored_product_id);
      elseif (empty($item['product_object']) && $key == 'flex_module') :
        foreach($item as $flex) :
          if (!empty($flex['product_object'])) :
            $object = $flex['product_object'];
            $sponsored_product_id = $object->ID;
            array_push($products_args['post__not_in'], $sponsored_product_id);
          endif;
        endforeach;
      endif;
    endforeach;
  endforeach;
endif;

$products = get_category_distributed_posts($products_args, $taxonomy, $category);

if( !empty($products)) :
  /**
   * PHASE is each top level cycle - Contains 4 GROUPS
   * GROUP is each column - Contains 2 or 4 products + a sponsored content piece
   * ITEM is each piece of content, be it a product, sponsored product, ad, or callout
   */
  $index_phase = 0;
  $index_group = 0;
  $index_item = 0;

  $index_flex = 0;
  $ad_placement = 1; // starts as 1 so that the first ad is never on the top row
  $product_total = count($products);
  $product_remainder = $product_total % 10;
  $required_count = 2;
  foreach( $products as $key => $product) :
    $product_id = $product->ID;
    $product_title = $product->post_title;
    $product_category = $product->category;
    $sponsored_type = $index_sponsor[$index_group];
    $big_boi = false;

    /**
     * PHASE START:
     * At the beginning of each GROUP,
     * we inject a sponsored piece of content
     */
    if ($index_item == 0) :
      $group_classes = array('product-guide-grid__group');
      $boi = 3;
      if ($index_group == 0) {
        $boi += 2;
        array_push($group_classes, 'product-guide-grid__group--wide');
      }

      echo '<ul class="' . implode($group_classes, " ") . '">';
    endif;

    /**
     * SPONSORED CONTENT:
     * At the beginning of each GROUP,
     * we inject a sponsored piece of content
     */
    if ($index_item == 0) :
      $sponsored_item = true;
      $generate_placement = rand ( 1, 3 );
      while ($ad_placement == $generate_placement) {
        $generate_placement = rand ( 1, 3 );
      }
      $ad_placement = $generate_placement;

      $order = $index_group == 0 ? $ad_placement * 2 - 1 : $ad_placement;
      if ($index_group > 0) :
        $sponsored_content = isset($sponsored_content_items[$index_phase][$sponsored_type]) ? $sponsored_content_items[$index_phase][$sponsored_type] : null;
        $required_count = $sponsored_content ? 2 : 3;
        $big_boi = $required_count === 3 ? true : false;
      endif;

      // Flex items are an array, so if we're in a flex spot, we need to figure out which one to use
      // we'll do that by offsetting the index-group
      if ($sponsored_type == 'flex_module') :
        $sponsored_content = $sponsored_content[$index_flex];
        $index_flex++;
      endif;

      if ($index_group == 0) :
        require('product-guide-grid-ad.php');
        $ad_index++;
        $boi--;
      elseif (!empty($sponsored_content) && $index_group == 1) :
        require('product-guide-grid-product.php');
        $boi--;
      elseif (!empty($sponsored_content) && !empty($sponsored_content['module_type'])) :
        require('product-guide-grid-' . $sponsored_content['module_type'] . '.php');
        $boi--;
      endif;
    endif;

    // Then we inject a product
    if (!$big_boi) :
      $order = $index_item + 1;
    endif;
    $sponsored_item = false;
    require('product-guide-grid-product.php');
    $boi--;
    $big_boi = false;
    $index_item++;

    if (($index_group == 0 && $index_item == 4) || ($index_group != 0 && $index_item == $required_count)) :
      $index_group++;
      $index_item = 0;
      $required_count = 2;
      echo '</ul>';
    elseif ($key === count($products) - 1) :
      for ($i = 1; $i <= $boi; $i++) : ?>
        <li class="product-guide-grid__item product-guide-grid__item--empty product-guide-grid--order-<?= $order + 1; ?>"></li>
      <?php
      endfor;
      echo "</ul>";
    endif;
    if ($index_group == 4) :
      $index_phase++;
      $index_group = 0;
      $index_flex = 0;
    endif;
  endforeach;

else : ?>
  <div class="product-guide-grid__no-products">
    <h2><?= $no_product_copy ?></h2>
  </div>
<?php
endif;
wp_reset_postdata(); ?>
