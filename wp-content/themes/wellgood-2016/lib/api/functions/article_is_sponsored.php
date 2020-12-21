<?php
/**
 * Article is sponsored
 * 
 * Similar to Branded, the backend_tag `sponsored_editorial`
 * will display "Sponsored" above article cards. In the 
 * future this may serve other purposes as well.
 * 
 * @FUTURE
 * This is not in use as of Nov 5 2020 because W+G 
 * changed strategy, but we have been told to keep
 * it around because it will be used in the future
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 16.3.2
 */

function article_is_sponsored( $post_id ) {
  $is_sponsored = has_term( 'sponsored_editorial', 'backend_tag', $post_id ) ? true : false;

  return $is_sponsored;
}
