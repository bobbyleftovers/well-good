<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Purges terms
 *
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Barrel
 */

class WG_Varnish_Purge_Term extends WG_Varnish_Purge {

  /**
	 * Current term object
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      object
	 */
	protected $term;

  /**
  * 
  * Fire purge process
  *
  * @since      1.0.1
  * @author     Barrel
  */
  public function purge( $post_id ) {

    if($this->skip_purge()) return;

    $post = $this->get_post( $post_id, false );

    //run purge
    switch(  $this->get_post_purge_level( $post_id, $post->post_type ) ):
      case 0:
        return;
      break;
      case 1:
      case 2:
        $this->primary_purge( $post_id );
        //don't break
      case 2;
        $this->schedule_secondary_purge( 'wg_varnish_secondary_purge_post', array($post_id), 10 );
    endswitch;

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
    $this->purge_urls($urls );

  }

  /**
  * 
  * Fire secondary purge process
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function secondary_purge( $post_id ) {

    $post = $this->get_post( $post_id, false );

    // Skip secondaary purge
    $skip = apply_filters( 'wg_varnish_purge_post_skip_secondary_purge', false, $post );
    if($skip) return;
 
    // Run secondary purge
    $urls = $this->get_secondary_purge_endpoints( $post_id );
    $this->purge_urls($urls );

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
    $post = $this->get_post( $post_id, false );

    $urls = array();

    // Post permalink
    $post_permalink = get_permalink( $post->ID  );
    $urls[] = $post_permalink;

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

    // Home Page
    $skip_home = apply_filters( 'wg_varnish_purge_post_skip_home', false, $post);
    if(!$skip_home) $urls = ['/'];

    // Taxonomy Pages
    $this->hydrate_taxonomies_endpoints($post, $urls);

    // Author Page
    $this->hydrate_author_page($post, $urls);

    $urls = apply_filters( 'wg_varnish_purge_post_secondary_endpoints', $urls, $post );

    return $urls;
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
        $urls[] = get_term_link($term);
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

  /**
  * 
  * Get post purge level
  *
  * @since      1.0.2
  * @author     Barrel
  */
  public function get_post_purge_level( $post_id ) { 

    //get post
    $post = $this->get_post( $post_id );
    
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
        return 0;
      break;
      default:
        if(wp_is_post_revision( $post->ID )) return 0;
        //no break!
      case 'private':
        return 1;
      break;
      case 'trash':
      case 'publish':
        return 2;
      break;
    endswitch;

    return 2;

  }

}