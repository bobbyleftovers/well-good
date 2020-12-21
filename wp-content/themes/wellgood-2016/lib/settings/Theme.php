<?php

namespace WG\Settings;

class Theme {

  static $custom_themes = array(
    'templates/page-summer.php',
    'templates/page-product-guide.php',
    'templates/page-2020-hub.php'
  );


  function __construct() {

    //Ini files globals
    $this->set_globals();

    //Disable Gutenberg editor for the time being...
    // add_filter('use_block_editor_for_post', '__return_false');
    //Theme support
    add_action( 'after_setup_theme', array($this, 'add_theme_support'));
    // Remove link rel
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    // Load custom meta tag
    add_action('wp_head', array($this,'add_meta_tags'));
    //body class
    add_filter( 'body_class', array($this,'body_class'));

    add_action( 'init', function() {
      add_rewrite_endpoint( 'slide', EP_PERMALINK );
    });
  }


  private function set_globals(){

		foreach(self::get_theme_globals() as $KEY => $GLOBAL){
      if(!defined($KEY)) define($KEY,$GLOBAL);
    }

  }


  public static function get_theme_globals(){
    // $ini_files = array_merge(glob(get_template_directory()."/config/*.ini"));

    $ini_files = array(
      get_template_directory()."/assets/scripts.ini"
    );

    $GLOB = array();

		foreach ($ini_files as $key => $filename) {
      $FILE = strtoupper(preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($filename)));
      if(file_exists($filename)) $GLOB = array_merge($GLOB, parse_ini_file($filename, true));
    }

    if(!is_admin() && !$GLOB) wp_die('You need to build the assets at least once to run the theme');

    return $GLOB;
  }


  function body_class( $classes ) {
    if(is_local()) $classes[] = 'is-local';
    if(is_dev()) $classes[] = 'is-dev';
    if(is_production()) $classes[] = 'is-production`';
    if(is_staging()) $classes[] = 'is-staging';
    $classes[] = str_replace('.','-',get_host());
    return $classes;
  }

  // Load custom meta tag
  function add_meta_tags() {
    ?>
    <meta name="pocket-site-verification" content="e5ab52bbadc2281f4803a907a07a48" />
    <?php
  }

  /**
   * Determine if were using a specified template and return a prefix.
   * This is used to conditionally include/exclude assets based specific template files.
   * Templates in the $templates array should have corresponding css and js files being generated in the /assets directory.
   */
  public static function get_theme_prefix() {
    if ( is_page_template( self::$custom_themes ) ) :
      $template = get_page_template();
      $template = explode( '/', get_page_template() );
      $template = end( $template );

      switch($template):
        case 'page-2020-hub.php':
          return 'trends-2020';
        default:
          $template_prefix = preg_match( '/-(.*)\./', $template, $file_prefix );
          return $file_prefix[1];
      endswitch;

    endif;

    return false;
  }


  function add_theme_support() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
  }
}
