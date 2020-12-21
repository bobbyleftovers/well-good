<?php
/**
 * Article is branded
 * 
 * Checks if article has `backend_tag` or legacy tag
 * `branded`. W+G used to use the tag taxonomy but later
 * switched to the `backend_tag` taxonomy to manage 
 * branded content, so for backwards compatibility we
 * still need to check that taxonomy as well
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 14.0.0
 */

function article_is_branded( $post_id ) {
  $is_branded = has_term( 'branded', 'backend_tag', $post_id ) || has_tag( 'branded', $post_id ) ? true : false;

  return $is_branded;
}
