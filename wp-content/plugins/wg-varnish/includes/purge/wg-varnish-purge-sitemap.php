<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Purges sitemap XML
 *
 *
 * @since      1.1.0
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Barrel
 * 
 * 
 * Yoast sitemaps
 * /sitemap_index.xml
 * /sitemap(_index)?\.xml(\.gz)?[a-z0-9_\-]*sitemap[a-z0-9_\-]*\.(xml|xsl|html)(\.gz)?
 * /([a-z0-9_\-]*)?\.xml
 * 
 */

class WG_Varnish_Purge_Sitemap extends WG_Varnish_Purge {

  /**
  * 
  * Fire purge process
  *
  * @since      1.1.0
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
  * @since      1.1.0
  * @author     Barrel
  */
  public function hydrate_urls( &$urls = array(), $filters = array() ) {

    // Global filter
    $filters[] = apply_filters( 'wg_varnish_purge_sitemap_skip', false);

    // Test if skipped and add url
    if(!$this->skip($filters)) $urls[] = '/sitemap_index.xml';

    // return urls
    return $urls;

  } 

  /**
  * 
  * Skip purge process
  *
  * @since      1.1.0
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