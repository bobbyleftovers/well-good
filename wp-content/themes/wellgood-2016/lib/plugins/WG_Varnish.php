<?php

namespace WG\Plugins;

class WG_Varnish {

  private $allow_any_host = true;

  /**
  *
  * Filer endpoints to reduce payload
  *
  */
  function __construct() {


    // Skip purge staging
    add_filter('wg_varnish_purge_skip_staging',array($this,'skip_purge_staging'), 10, 2);

    // Purge post (any post_type)
    add_filter('wg_varnish_purge_post_skip',array($this,'purge_post_skip'), 10, 2);
    add_filter('wg_varnish_purge_post_primary_endpoints',array($this,'purge_post_primary_endpoints'), 10, 3);
    add_filter('wg_varnish_purge_post_skip_secondary_purge',array($this,'purge_post_skip_secondary'), 10, 2);
    add_filter('wg_varnish_purge_post_skip_home',array($this,'purge_post_skip_home'), 10, 2);
    add_filter('wg_varnish_purge_post_skip_taxonomy',array($this,'purge_post_skip_taxonomy'), 10, 3);
    add_filter('wg_varnish_purge_post_skip_author',array($this,'purge_post_skip_author'), 10, 2);

    // Skip sitemap
    add_filter('wg_varnish_purge_sitemap_skip','__return_true');

    // Skip feeds
    add_filter('wg_varnish_purge_feed_skip','__return_true');

    // Add main endpoints to feed
    // add_filter('wg_varnish_purge_feed_main_endpoints',array($this,'feed_main_endpoints'));

    // Allow notifications and sql logs
    add_filter('wg_varnish_notices',array($this,'allow_logs_and_notices'));
    add_filter('wg_varnish_db_logs',array($this,'allow_logs_and_notices'));

  }

  /**
  *
  * Skip or allow logs and notices
  *
  */
  function allow_logs_and_notices($allow){

    if(is_production()) return false;

    return $allow;

  }

  /**
  *
  * Skip staging purge checking env
  * Default: true
  *
  */
  function skip_purge_staging($skip, $host){

    if( $this->allow_any_host ) return false;

    if($host === 'varnish.stg.wellandgood.com') return false;

    return true;

  }

  /**
  *
  * Filer purge post
  *
  */
  function purge_post_skip($skip, $post){

    // public post types
    $allowed_post_types = array('post', 'page',  'mailing_list');

    if(!in_array($post->post_type, $allowed_post_types)) return true;

    //return original value
    return $skip;
  }

  /**
  *
  * Filer purge post primary endpoints
  *
  */
  function purge_post_primary_endpoints($urls, $post, $post_permalink){

    // add amp page
    if($post->post_type === 'post') $urls[] = $post_permalink.'/amp';

    //return endpoints
    return $urls;
  }

  /**
  *
  * Skip home when purging a post
  *
  */
  function purge_post_skip_home($skip, $post){

    //post types that affect home page
    $allowed_post_types = array('post');

    if(!in_array($post->post_type, $allowed_post_types)) return true;

    //return original value
    return $skip;
  }

  /**
  *
  * Skip taxonomies when purging a post
  *
  */
  function purge_post_skip_taxonomy($skip, $post, $taxonomy){

    //post types with public taxonomies
    $allowed_post_types = array('post');

    //taxonomies with public pages
    $allowed_taxonomies = array('category', 'post_tag');

    //no taxonomies for post types that are not posts
    if(!in_array($post->post_type, $allowed_post_types)) return true;

    //filter taxonomy
    if(!in_array($taxonomy->name, $allowed_taxonomies)) return true;

    //return original value
    return $skip;
  }

  /**
  *
  * Skip post secondary purge
  *
  */
  function purge_post_skip_secondary($skip, $post){

    if($post->post_type !== 'post') return true;

    return $skip;
  }

  /**
  *
  * Skip post author purge
  *
  */
  function purge_post_skip_author($skip, $post){

    if($post->post_type !== 'post') return true;

    return $skip;
  }

  /**
  *
  * Add Main feed endpoints
  *
  */
  function feed_main_endpoints($endpoints){

    $endpoints[] = '/feed/slack';

    return $endpoints;

  }
}
