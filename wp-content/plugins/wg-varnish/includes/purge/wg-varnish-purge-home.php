<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Purges home
 *
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Barrel
 */

class WG_Varnish_Purge_Home extends WG_Varnish_Purge {

  /**
  * 
  * Fire purge process
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function purge( $filters = array() ) {

    // Purge urls
    $this->purge_urls( $this->hydrate_urls() );

  } 

  /**
  * 
  * Add urls
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function hydrate_urls( &$urls = array(), $filters = array() ) {

    // Global filter
    $filters[] = apply_filters( 'wg_varnish_purge_home_skip', false);

    // Test if skipped and add url
    if(!$this->skip($filters)) {
      $urls[] = '/';
    }

    // return urls
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

      if($this->skip_purge()) return true;

      foreach($filters as $skip) {
          if( $skip ) return true;
      }

      return false;
  }
}