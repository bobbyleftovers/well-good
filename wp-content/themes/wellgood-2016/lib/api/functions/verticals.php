<?php

/**
 *
 * Verticals are groups used to categories.
 * They are set in Theme Options > Vertical Options
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 10.0.0
 */

use \WG\Schema\Taxonomies\Verticals;
use WG\Content\Title;



/**
 * @param integer $hero_tag
 * @return $formatted_vertical
 */
function get_vertical( $hero_tag = null, $format = FALSE ) {

  if(!$hero_tag && is_category()) $hero_tag = get_queried_object()->term_id;
  
  $vertical = Verticals::get_vertical_from_hero( $hero_tag );

  if ( $format ) :
    $vertical = Title::filter_the_title( $vertical );
  endif;

  return $vertical;
}

/* get all verticals **/
function get_verticals() {
  return Verticals::get_verticals();
}

/* get current vertical object **/
function get_current_vertical(){
  return Verticals::get_current_vertical();
}

/* gcheck if is vertical endpoint **/
function is_vertical(){
  return Verticals::is_vertical();
}

