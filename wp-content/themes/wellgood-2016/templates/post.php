<?php
/**
 * Post Template
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 3.1.3
 */


// Post Data
$post_id = $post->ID;
$post_type = $post->post_type;
$hero_tag = get_field( 'hero_tag', $post_id );
$vertical = get_vertical( $hero_tag );
$branded = article_is_branded( $post_id );
$blacklisted = partner_is_blacklisted( $post_id );
$infinite_preset = get_infinite_preset( $vertical, $post_id );


// Wrapper Attributes
$wrapper_attributes = array();
$wrapper_attributes[] = 'class="post-content__infinite-wrapper"';
if ( ! $branded && ! $blacklisted ) :
  $wrapper_attributes[] = 'data-module-init="infinite-scroll"';
endif;

// Infinite Scroll Attributes
$infinite_scroll_attributes = array();
$infinite_scroll_attributes[] = 'class="infinite-scroll load-more-indicator"';
$infinite_scroll_attributes[] = "data-post-id=\"$post_id\"";
$infinite_scroll_attributes[] = "data-vertical=\"$vertical\"";
if ( $infinite_preset ) :
  $infinite_scroll_attributes[] = "data-preset-id=\"$infinite_preset->ID\"";
endif;
?>


<div <?= implode( ' ', $wrapper_attributes ) ?>>
  <?php the_module( "$post_type-content", array(
    'post_id' => $post_id
  ) ); ?>
  <div <?= implode( ' ', $infinite_scroll_attributes ); ?>>
    <p class="load-more-indicator__text not-showing">Loading More Posts...</p>
  </div>
</div>


<?php
if ( ! $branded ) :
  the_module( 'collection', 'collection' );
endif;
