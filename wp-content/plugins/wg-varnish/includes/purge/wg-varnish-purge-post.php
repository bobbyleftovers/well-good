<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Purges posts
 *
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Barrel
 */

class WG_Varnish_Purge_Post extends WG_Varnish_Purge {

  /**
	 * Current post object
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      object
	 */
  protected $post;

  /**
  * 
  * Fire purge process on post update
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function purge_on_update( $post_id,  $post_after, $post_before ) {

    $this->purge( $post_after, true);

  }
  

  /**
  * 
  * Fire purge process
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function purge( $post ) {

    if($this->skip_purge()) return;

    if(!is_object($post)) $post = $this->get_post( $post );

    //run purge
    switch(  $this->get_post_purge_level( $post ) ):
      case 0:
        return;
      break;
      case 1:
      case 2:
        $urls = $this->get_primary_purge_endpoints( $post->ID );
        //$this->primary_purge( $post->ID );
        //don't break
      case 2:
        //$urls = array_merge($urls, $this->get_secondary_purge_endpoints( $post->ID ));
        $this->schedule_secondary_purge( 'wg_varnish_secondary_purge_post', array( $post->ID ), 10 );
    endswitch;

    $this->purge_urls( $urls );
  }

  /**
  * 
  * Get post purge level
  *
  * @since      1.0.2
  * @author     Barrel
  */
  public function get_post_purge_level( $post ) { 
    
    // Post doesn't exist
    if( !$post ) return 0;

    // Skip post
    $skip = apply_filters( 'wg_varnish_purge_post_skip', false, $post );
    if($skip) return 0;

    //check post status
    switch($post->post_status):
      case 'draft':
      case 'auto-draft':
      case 'pending':
      case 'future':
      case 'private':
        return 0;
      break;
      default:
        if(wp_is_post_revision( $post->ID )) return 0;
        //no break!
      break;
      case 'trash':
      case 'publish':
        return 2;
      break;
    endswitch;

    return 2;

  }


  /**
  * 
  * Fire main purge process
  *
  * @since      1.0.1
  * @author     Barrel
  */
  function primary_purge( $post_id ) {
 
    $urls = $this->get_primary_purge_endpoints( $post_id );
    return $this->purge_urls( $urls );

  }

  /**
  * 
  * Fire secondary purge process
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function secondary_purge( $post_id ) {

    $urls = $this->get_secondary_purge_endpoints( $post_id );
    return $this->purge_urls( $urls );

  }

  /**
  * 
  * Primary purge endpoints 
  * fired on post_save
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function get_primary_purge_endpoints( $post_id ) {
    $post = $this->get_post( $post_id );

    $urls = array();

    // Post permalink
    $post_permalink = get_permalink( $post->ID  );
    $urls[] = $post_permalink;
    
    // Add home
    $this->hydrate_home( $post_id, $urls );

    $urls = apply_filters( 'wg_varnish_purge_post_primary_endpoints', $urls, $post, $post_permalink );

    return $urls;
  }

  /**
  * 
  * secondary purge endpoints
  * fired on cronjob
  *
  * @since      1.0.2
  * @author     Barrel
  */
  public function get_secondary_purge_endpoints( $post_id ) {

    $post = $this->get_post( $post_id, true );

    // Skip secondaary purge
    $skip = apply_filters( 'wg_varnish_purge_post_skip_secondary_purge', false, $post );
    if($skip) return array();

    // Taxonomy Pages
    $this->hydrate_taxonomies_endpoints($post, $urls);

    // Author Page
    $this->hydrate_author_page($post, $urls);

    // Add home
    $this->hydrate_home( $post_id, $urls );
    $this->hydrate_feeds( $post_id, $urls );
    $this->hydrate_sitemap( $post_id, $urls );

    $urls = apply_filters( 'wg_varnish_purge_post_secondary_endpoints', $urls, $post );

    return $urls;
  }

  /**
  * 
  * Purge home
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function hydrate_home( $post_id, &$urls ) {

    $post = $this->get_post( $post_id );

    // Filter and hydrate url
    $this->loader->load('Purge/WG_Varnish_Purge_Home')->hydrate_urls( $urls, array(
        apply_filters( 'wg_varnish_purge_post_skip_home', false, $post),
    ));
    
  }

  /**
  * 
  * Purge feeds
  *
  * @since      1.1.0
  * @author     Barrel
  */
  public function hydrate_feeds( $post_id, &$urls ) {

    $post = $this->get_post( $post_id );

    // Filter and hydrate url
    $this->loader->load('Purge/WG_Varnish_Purge_Feed')->hydrate_urls( $urls, array(
        apply_filters( 'wg_varnish_purge_post_skip_main_feeds', false, $post),
    ));

    return $urls;
    
  }

  /**
  * 
  * Purge sitemap
  *
  * @since      1.1.0
  * @author     Barrel
  */
  public function hydrate_sitemap( $post_id, &$urls ) {

    $post = $this->get_post( $post_id );

    // Filter and hydrate url
    $this->loader->load('Purge/WG_Varnish_Purge_Sitemap')->hydrate_urls( $urls, array(
        apply_filters( 'wg_varnish_purge_post_skip_sitemap', false, $post),
    ));
    
  }

  /**
  * 
  * Purge added endpoints (added by filter)
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function hydrate_filter_endpoints( $post_id, &$urls ) {

    $post = $this->get_post( $post_id );

    // Filter and hydrate url
    $this->loader->load('Purge/WG_Varnish_Purge_Home')->hydrate_urls( $urls, array(
        apply_filters( 'wg_varnish_purge_post_skip_home', false, $post),
    ));
    
  }


  /**
  * 
  * Add ALL terms from ALL taxonomies
  *
  * @since      1.0.2
  * @author     Barrel
  */

  function hydrate_taxonomies_endpoints( $post, &$urls ){

    // Filter taxonomies to purge
    $taxonomies = apply_filters( 'wg_varnish_purge_post_taxonomies', get_object_taxonomies($post->post_type, 'objects'), $post->post_type );
  
    // Add taxonomies endpoints
    foreach($taxonomies as $taxonomy){
      
      // Taxonomy has no public enpoints
      if(!$taxonomy->public) continue;

      // Skip taxonomy filter
      $skip = apply_filters( 'wg_varnish_purge_post_skip_taxonomy', false, $post, $taxonomy);
      if($skip) continue;

      // Get all terms endpoints
      $terms = get_the_terms($post, $taxonomy->name);
      if(!$terms) continue;
      foreach($terms as $term){
        $link = get_term_link($term);
        $urls[] = $link;
        $this->loader->load('Purge/WG_Varnish_Purge_Feed')->hydrate_endpoint_feed( $link, $urls, array(
          apply_filters( 'wg_varnish_purge_post_skip_feed', false, $post)
        ));
      }
    }
    return $urls;
  }

  /**
  * 
  * Add author page
  *
  * @since      1.0.2
  * @author     Barrel
  */

  function hydrate_author_page( $post, &$urls ){

    //filter skip
    $skip = apply_filters( 'wg_varnish_purge_post_skip_author', false, $post);
    if($skip) return $urls;

    //add author endpoint
    if(!$post->post_author) return $urls;
    $urls[] = get_author_posts_url($post->post_author, get_the_author_meta( 'user_nicename' , $post->post_author));
    return $urls;
  }

  /**
  * 
  * Get current post object
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function get_post( $post_id, $force_query = false ) { 

    global $post;

    if(isset($this->post) && $this->post) return $this->post;

    if(!$force_query && isset($post) && $post) {

      $this->post = $post;

    } else {

      $this->post = get_post( $post_id );

    }

    return $this->post;

  }

}