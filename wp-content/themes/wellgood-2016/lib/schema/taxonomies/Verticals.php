<?php

namespace WG\Schema\Taxonomies;

class Verticals {

  function __construct(){

    //query var registration
    add_filter( 'query_vars', array($this,'register_query_vars') );

    // RSS feeds
    add_filter('generate_rewrite_rules', array($this,'verticals_feeds_rewrite_rules'), 99);
    add_filter('acf/save_post',array($this,'flush_rewrite_rules_on_update_verticals'));
    add_filter('wp_title_rss', array($this,'vertical_feed_title_rss'), 20, 1);
    add_filter('get_wp_title_rss', array($this,'vertical_feed_title_rss'), 20, 1);
    add_filter('wg_rss2_template', array($this,'rss2_template'));

  }

  /**
   * checks if current endpoint is a vertical (currently only used for feeds)
   */
  static function is_vertical(){
    $vertical_var = get_query_var('vertical');
    if(!isset($vertical_var) || $vertical_var === '') return false;
    return true;
  }

  /**
   * Get current vertical endpoint object
   */
  static function get_current_vertical(){
    if(!is_vertical()) return false;
    global $vertical;
    if(is_array($vertical)) return $vertical;
    $verticals = get_verticals();
    $vertical_name = get_query_var('vertical');
    $verticals = array_filter($verticals, function($val) use ($vertical_name){ return $val['verticals_name'] === $vertical_name;});
    if(sizeof($verticals) === 0) $vertical = false;
    else {
      $verticals = array_slice($verticals, 0, 1);
      $vertical = $verticals[0];
    }
    if($vertical){
      $vertical['name'] = $vertical['verticals_name'];
      $vertical['slug'] = sanitize_title_with_dashes($vertical['verticals_name']);
      $vertical['categories'] = $vertical['verticals_editorial_tags'];
    }
    return $vertical;
  }

  /**
   * Get all verticals
   */
  static function get_verticals(){
    global $verticals;
    if(!$verticals) $verticals = get_field("verticals", "option");
    return $verticals;
  }

  /**
   * Get vertical from a specific hero
   */
  static function get_vertical_from_hero($hero_tag){
    $vertical = '';
    $verticals = get_verticals();
    if ( ! $verticals || ! $hero_tag ) :
      return $vertical;
    endif;

    foreach( $verticals as $vertical_list ) :
      if ( in_array( $hero_tag, $vertical_list['verticals_editorial_tags'] ) ) :
        $vertical = $vertical_list['verticals_name'];
      endif;
    endforeach;

    $formatted_vertical = strtolower( str_replace( ' ', '_', $vertical ) );

    return $formatted_vertical;
  }

  /**
   * Register 'vertical' query var
   */
  function register_query_vars( $vars ) {
    $vars[] = 'vertical';
    return $vars;
  }

  /*
  * RSS: verticals feed flush rules
  */
  function flush_rewrite_rules_on_update_verticals($post){
    if($post != 'options' || $_GET['page'] !== 'acf-options-vertical-options') return;
    flush_rewrite_rules();
  }

  /*
  * RSS: verticals feed write rules
  */
  function verticals_feeds_rewrite_rules ($wp_rewrite) {

    $verticals = get_verticals();
    $rules = array();

    if ($verticals && is_array($verticals)) {
      foreach ($verticals as $vertical) {
        $slug = sanitize_title_with_dashes($vertical['verticals_name']);
        $query = '&vertical='.$vertical['verticals_name'];
        $query .= '&cat='.implode(',',$vertical['verticals_editorial_tags']);
        $rules[$slug.'/?$'] = 'index.php?feed=feed'.$query;
        $rules[$slug.'/(?:feed/)/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?&feed=$matches[2]'.$query;
        $rules[$slug.'/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?feed=$matches[1]'.$query;
      }
    }

    $wp_rewrite->rules = $rules + $wp_rewrite->rules;

  }

  /*
  * RSS: verticals feed title
  */
  function vertical_feed_title_rss($title){
    if(!is_vertical()) return $title;
    $vertical = get_current_vertical();
    return $vertical['name'];
  }

  /*
  * RSS: rss2 template do_feed_rss2
  */
  function rss2_template($template){
    if(is_vertical()) return get_template_directory() . "/templates/feeds/vertical.php";
    return $template;
  }
}
