<?php
/**
 * Category link displayed above each product on the grid
 * @author BarrelNY
 *
 * The categories are not linking to actual category links, but
 * product pages that were created as child pages
 *
 * required from `product-guide-grid.php`
 */

foreach($product_categories as $category) :
  $category_id = $category->term_id;
  $category_name = $category->name;
  $category_pages = new WP_Query(array(
    'post_type'       => 'page',
    'child_of'        => $parent_id,
    'meta_query'      => array( array(
      'key'             => 'product_guide_product_category',
      'value'           => $category_id,
      'compare'         => 'LIKE'
    ))
  ));
  if( $category_pages->have_posts() ) :
    while( $category_pages->have_posts() ) :
      $category_pages->the_post();
      $id = get_the_id();
      $title = get_the_title();
      $url = get_the_permalink();
      $category_href = '';
      $category_classes = array();
      if ($page_id != $id) :
        $category_href = 'href="'. $url .'" ';
        array_push($category_classes, 'load-products');
      endif;
      ?>

      <a <?= $category_href; ?>class="<?= implode(' ', $category_classes) ?>" data-url="<?= $url; ?>" data-title="<?= $title; ?>" data-id="<?= $id; ?>">
        <h5><?= $category_name; ?></h5>
      </a>

    <?php
    endwhile;
  else : ?>
    <span>
      <h5><?= $category_name; ?></h5>
    </span>
  <?php
  endif;
  wp_reset_postdata();
endforeach; ?>
