<?php

namespace WG\API\REST;

class Hub_Locations {

  function __controller(){
    add_filter( "rest_hub-locations_query", array($this, 'add_location_order_param'), 10, 2 );
  }

  // Support for 'location_order=menu_order' for the 'hub-locations' post type called via REST API v2
  function add_location_order_param( $args, $request ){
      if( 'menu_order' === $request->get_param( 'location_order' ) )
          $args['orderby'] = 'menu_order';

      return $args;
  }

}