<?php

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Location_Hub_Link extends Custom_Shortcode {
  function shortcode( $atts ) {
    $label = $atts['label'] ? $atts['label'] : 'Open Location';
    $city_var = $atts['city'];
    $position = $atts['position'] ? $atts['position'] : '';

    // Get term by id if it's an ID
    if ( is_numeric($city_var) ) {
      $city = get_term_by('id', $city_var, 'cities');

    // Get term by slug name
    } else {
      $city = get_term_by('slug', $city_var, 'cities');
    }

    // Get term by name if slug or ID didn't work
    if (!$city) {
      $city = get_term_by('name', $city_var, 'cities');
    }

    // Only return link if a city was found
    if ($city) {
      return '<div class="lochub-map-trigger '. $position .'"><a name="#modal-map" class="btn filled js-open-modal-maps" data-city="'.$city->term_id.'" data-vars-event="open map">'.$label.'</a></div>';
    } else {
      return '';
    }
  }

}
