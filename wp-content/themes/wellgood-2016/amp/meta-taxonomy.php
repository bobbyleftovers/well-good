<?php

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */


use WG\Schema\Taxonomies\Category;


$categories = get_the_category( $this->ID );
?>
<?php if ( $categories ) : 
  $legacy_categories = array('
  ');
  $categories = array_map( function( $category ) {
    $link = get_category_link( $category );
    return '<a href="' . $link . '" rel="category tag">' . $category->name . '</a>';
  }, Category::filter_legacy_categories( $categories ) );
  
  if ($categories) : ?>
	<div class="amp-wp-meta amp-wp-tax-category">
		<?php
    /* translators: %s: list of categories. */
		printf( esc_html__( 'Tags: %s', 'amp' ), join(', ', $categories )); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
  <?php 
  endif;
endif; 
