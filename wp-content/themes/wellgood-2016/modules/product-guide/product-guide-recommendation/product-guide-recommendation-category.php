<?php
/**
 * Category link displayed above each recommendation in the quiz results
 * @author BarrelNY
 *
 * The categories are not linking to actual category links, but
 * product pages that were created as child pages
 *
 * required from `product-guide-recommendation.php`
 */

foreach($recommendation_categories as $category) :
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
      $link = 'href="'.get_the_permalink().'" ';
      ?>

      <a <?= $link; ?>class="product-guide-recommendation__info--category">
        <h5><?= $category_name; ?></h5>
      </a>

    <?php
    endwhile;
  else : ?>
    <div class="product-guide-recommendation__info--category">
      <h5><?= $category_name; ?></h5>
    </div>
  <?php
  endif;
  wp_reset_postdata();
endforeach; ?>
