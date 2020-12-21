<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Purges feed
 *
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Barrel
 * 
 * WG feeds
 * /feed
 * /feed/slack
 * /<slug>/feed
 * /tag/<slug>/feed
 * 
 */

class WG_Varnish_Purge_Feed extends WG_Varnish_Purge {

  /**
  * 
  * Fire purge on main feeds
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function purge_main_feeds( $filters = array() ) {

    $endpoints = $this->get_main_feeds( $filters );

    // Purge urls
    if(sizof($endpoints) > 0) $this->purge_urls( $endpoints );

  } 

  /**
  * 
  * Get main feeds
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function get_main_feeds( $filters = array() ) {

    $filters[] = apply_filters( 'wg_varnish_purge_feed_skip_main', false);

    if($this->skip($filters)) array();

    return apply_filters( 'wg_varnish_purge_feed_main_endpoints', array('/feed'));

  } 

  /**
  * 
  * Add main urls
  *
  * @since      1.1.0
  * @author     Barrel
  */
  public function hydrate_urls( &$urls = array(), $filters = array() ) {

    // Test if skipped and add url
    if(!$this->skip($filters)) {
      $urls = array_merge($urls, $this->get_main_feeds());
    }

    // return urls
    return $urls;
  }

  /**
  * 
  * Hydrate feed to endpoints
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function hydrate_endpoint_feed( $endpoint = '', &$urls, $filters = array() ) {

    if( $this->skip($filters) ) return $urls;

    $urls[] = $endpoint.'/feed';

    return $urls;
  }

  /**
  * 
  * Skip purge process
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function skip( $filters = array() ) { 
    
    // Global filter
    $filters[] = apply_filters( 'wg_varnish_purge_feed_skip', false);

    if($this->skip_purge()) return true;

    foreach($filters as $skip) {
          if( $skip ) return true;
      }

    return false;
  }
}