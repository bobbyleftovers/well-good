<?php

namespace WG\Settings;

class  Permalinks {

    static $custom_static_routes = array(
      'styleguide' => array(
        'template' => 'styleguide-2020',
        'title' => 'Styleguide'
      )
    );

    function __construct(){

      // Create new permalink rules
      add_filter('generate_rewrite_rules', array($this,'generate_rewrite_rules'));

      // Redirect old urls
      add_action('template_redirect', array($this,'template_redirect'));

      // Rebuild permalink
      add_filter('category_link', array($this,'category_link'),1000,2);

      //flush rules on cat save
      add_action( 'edit_term', array($this,'on_save_category'), 10, 3 );

      // add custom static routes
      add_filter( 'generate_rewrite_rules', array($this,'custom_static_routes_generate_rules'));
      add_filter( 'template_include', array($this, 'custom_static_route_template_include') );
      add_filter( 'document_title_parts', array($this, 'custom_static_route_title'));
      add_filter( 'query_vars', array($this, 'custom_static_routes_register_query_vars') );

      // @WORK TEMP
      //Alter amp request
      add_filter( 'request', array($this,'alter_amp_request_query') );
      // /WORK TEMP

    }

    function flush_rules(){
        $wp_rewrite = new \WP_Rewrite;
        $wp_rewrite->flush_rules();
    }

    function on_save_category($term_id, $tt_id, $taxonomy) {
        if($taxonomy !== 'category') return;
        $this->flush_rules();
    }


    /** permalinks rules */

    function generate_rewrite_rules($wp_rewrite) {
      $rules = array();
      $terms = get_terms( array(
          'taxonomy' => 'category',
          'hide_empty' => false,
      ) );

      foreach ($terms as $term) {

        /** flatten all categories links */

        if($term->parent !== 0) {

          $rules[$term->slug.'$'] = 'index.php?cat='.$term->term_id;
          $rules[$term->slug.'/(?:feed/)/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?cat='.$term->term_id.'&feed=$matches[2]';
          $rules[$term->slug.'/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?cat='.$term->term_id.'&feed=$matches[1]';
          $rules[$term->slug.'/page/?([0-9]{1,})/?$'] = 'index.php?cat='.$term->term_id.'&paged=$matches[2]';
          $rules[$term->slug.'/?$'] = 'index.php?cat='.$term->term_id;

        }

      }

      $keys = array_map('strlen', array_keys($rules));
      array_multisort($keys, SORT_DESC, $rules);

      $wp_rewrite->rules = $rules + $wp_rewrite->rules;

      do_action('wg_rewrite_rules_generated', $rules, $wp_rewrite->rules);

    }

    /** flatten all categories links */

    function category_link($catlink, $category_id) {
        $category = get_category( $category_id );
        if ( is_wp_error( $category ) )
            return $category;
        $category_nicename = $category->slug;

        $catlink = trailingslashit(get_option( 'home' )) . user_trailingslashit( $category_nicename, 'category' );

        return $catlink;
    }

    function template_redirect() {

      $post_type = get_post_type();

      if (is_main_query() && is_single() && ( empty( $post_type ) || ($post_type === 'post') || ($post_type === 'recipe') ) ) {
        global $post;

        $permalink = trim(get_permalink( $post ), '/');

        $contains_query = strpos($permalink,'?');

        if($contains_query || $contains_query === 0) return;

        if(is_amp_endpoint()) {
          $permalink .= '/amp';
        };

        $link = explode("?",(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

        $actual_link = trim($link[0],'/');
        $query = isset($link[1]) ? '?'.$link[1] : '';

        if(strpos($actual_link,$permalink) !== 0){
          wp_safe_redirect( $permalink.'/'.$query, 301 );
          exit();
        }

      }
    }


    // @WORK TEMP
    function alter_amp_request_query($request){

      $query = new \WP_Query();  // the query isn't run if we don't pass any query vars
      $query->parse_query( $request );

      if($query->is_single() && isset($request['name']) && strpos($_SERVER['REQUEST_URI'],$request['name'].'/amp') !== false){
        $request['amp'] = true;
        add_action('pre_get_posts', function($query){
          $query->set( 'amp', 1 );
        });
      }

      return $request;
    }
    // /WORK TEMP


    /**
     * @WARNING:
     * don't use this function in filters, will break functionality
     *
     * @param [type] $link
     * @return void
     */
    static function filter_proxy_permalink( $link ) {
      if ( ! is_dev() ) :
        return $link;
      endif;

      return str_replace( "well-good.lndo.site", "localhost:3000", $link );
    }

    /**
     * maps get_permalink, but making sure it returns the proxied url
     *
     * @param integer $id
     * @return void
     */
    static function get_proxy_permalink( $id = null ) {
      return self::filter_proxy_permalink(get_permalink($id));
    }

    /**
    * Add custom routes rules
    */
    function custom_static_routes_generate_rules( $wp_rewrite ) {
      $rules = array();
      foreach ( self::$custom_static_routes as $route => $page ) :
        $template = $page['template'];
        $rules[$route] = "index.php?static=$template";
      endforeach;
      $wp_rewrite->rules = $rules + $wp_rewrite->rules;
    }

    /**
    * Add custom routes template include
    */
    public function custom_static_route_template_include($template) {
      global $wp_query;
      foreach ( self::$custom_static_routes as $route => $page ) :
        $custom_template = $page['template'];
        if ( $custom_template === get_query_var( 'static' ) ) {
          $wp_query->is_404 = false;
          $template = get_template_directory() . "/templates/static-$custom_template.php";
        }
      endforeach;

      return $template;
    }

    /**
    * Add custom routes title
    */
    public function custom_static_route_title($title_arr) {
      global $wp_query;
      foreach ( self::$custom_static_routes as $route => $page ) :
        if ( $page['template'] === get_query_var( 'static' ) ) {
          $wp_query->is_404 = false;
          $title_arr['title'] = $page['title'];
        }
      endforeach;
    
      return $title_arr;
    }

    /**
    * Register custom routes vars
    */
    function custom_static_routes_register_query_vars($vars){
      $vars[] = 'static';
      return $vars;
    }
}
