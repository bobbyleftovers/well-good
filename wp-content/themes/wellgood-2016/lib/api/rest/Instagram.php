<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use WG\Services\Instagram as InstagramFeed;

class Instagram extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/instagram/feed',
      'callback' => 'feed',
      'methods'   => array( 'GET')
    )
  );

  /*
  *  Get Feed
  */
  static function feed( ) {
    $ig = new InstagramFeed();
    return $ig->get_feed();
  }



}

