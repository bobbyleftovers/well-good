<?php
/**
 * Post tags
 *
 * Displays the editorial tags at the bottom
 * of an article
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */

use WG\Schema\Taxonomies\Category;


$categories = Category::filter_legacy_categories( get_the_category() );
$editorialtags = array_map( function( $cat ) {
  $cat_link = get_category_link( $cat->term_id );
  $cat_name = $cat->name;
  $classes = implode( ' ', array(
    'no-underline',
    'text-seafoam-dark',
    'hover-underline-seafoam-dark'
  ) );

  return "<a class='$classes' href='$cat_link'>$cat_name</a>";
}, $categories );

if ( $editorialtags ) : ?>
  <div class="flex justify-center mt-e38 mb-e10 order-1 text-label text-seafoam-dark md:mb-e18">
    <div>Tags: <?= implode(', ', $editorialtags); ?></div>
  </div>
<?php 
endif;
