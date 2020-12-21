<?php

namespace WG\Settings;
use \WG\Settings\Theme;

class Scripts {

  protected $no_main_style = array('product-guide');

  private $is_defined_theme = false;
  private $has_critical = false;
  private $theme_scripts_js = array();
  private $theme_scripts_css = array();
  private $theme_critical_css = array();
  private $need_vendor = array('main', 'main-2020', 'summer');

  function __construct($init = true) {

    if (is_admin() || !$init) :
      return;
    endif;

    //global object
    global $WG_THEME_SCRIPTS;
    $WG_THEME_SCRIPTS = $this;

    //set env vars
    add_action( 'init', array( $this, 'set_theme_environment' ) );

    add_action( 'wp_head', array( $this, 'prepare_theme_scripts' ), 1 );
    add_action( 'wp_head', array( $this, 'add_theme_critical_styles' ), 3 );
    add_action( 'get_footer', array( $this, 'enqueue_theme_scripts' ), 1 );
    add_action( 'get_footer', array( $this, 'remove_theme_critical_styles' ), 2 );

    //dequeue
    add_action( 'wp_enqueue_scripts', array($this,'dequeue_scripts'), 999999 );

    //specific templates
    add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_recipe_chicory' ) );

    //modify default scripts
    add_filter( 'clean_url', array( $this, 'async_js' ) );

    //scripts inside content
    add_filter( 'the_content', array( $this, 'filter_ptags_on_scripts' ) );

    //remove emojis
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

  }

  /**
   * Theming helpers
   */

  function add_theme_template($template){
    add_body_class('theme-'.$template);
    $this->is_defined_theme = true;
    $this->theme_scripts_js[] = $template;
    $this->theme_scripts_css[] = $template;
  }

  function add_theme_js($template){
    $this->theme_scripts_js[] = $template;
  }

  function add_theme_css($template){
    $this->theme_scripts_css[] = $template;
  }

  function set_theme_critical_css($template){
    $this->theme_critical_css = $template;
  }

  /**
   * Basic getters
   */

  function get_scripts_directory(){
    return get_template_directory() . '/assets/';
  }

  function get_scripts_uri(){
    return get_template_directory_uri() . '/assets/';
  }

  function has_dev_file($name){
    if(is_dev() && defined('NODE_ENTRIES')) {
      if(!isset($this->node_entries)) $this->node_entries = explode(',', NODE_ENTRIES);
      return in_array( $name, $this->node_entries);
    };
    return is_dev();
  }

  function get_js($name){
    $src = $name . ( !$this->has_dev_file($name) ? '.min' : '' ) . '.js';
    
    return $this->get_scripts_uri() . $src;
  }

  function get_css($name, $directory = false){
    return ($directory ? $this->get_scripts_directory() : $this->get_scripts_uri()) . $name . ( !$this->has_dev_file($name) ? '.min' : '' ) . '.css';
  }

  function get_name($name){
    return 'wg-'.$name;
  }

  /**
   * Env vars
   *
   * @return void
   */
  function set_theme_environment() {
    $this->theme      = wp_get_theme();
    $this->theme_ver  = $this->theme->version;
  }

  /**
   * Theme Prefix
   *
   * @return void
   */
  function set_theme_prefix() {
    $this->template_prefix = Theme::get_theme_prefix();
    $this->template_critical_prefix = $this->get_critical_prefix();
  }

  /**
   * Determine if were using a specified template and return a prefix.
   * This is used to conditionally include/exclude assets based specific template files.
   * Templates in the $templates array should have corresponding css and js files being generated in the /assets directory.
   */
  function get_critical_prefix() {

    if( $this->theme_critical_css ) return $this->theme_critical_css;

    if ( $this->template_prefix ) :

      return $this->template_prefix;

    elseif ( is_category() ) :

      return 'category';

    endif;

    return '';
  }

  /**
   * Adds bundle by name
   *
   * @param [string] $name
   * @return void
   */
  function enqueue_bundle( $name, $js_dependencies = array(), $css_dependencies = array()) {
    $this->enqueue_style($name, $css_dependencies);
    $this->enqueue_script($name, $js_dependencies);
  }

  /**
   * Adds a script or style depending on environment
   *
   * @param [string] $name
   * @return void
   */
  function enqueue_style( $name, $dependencies = array()) {
    $is_dev = $this->has_dev_file($name);
    if($is_dev) return;

    call_user_func(
      ($is_dev ? 'wp_enqueue_script' : 'wp_enqueue_style'),
      $this->get_name($name),
      ($is_dev ? $this->get_js($name) : $this->get_css($name)),
      $dependencies,
      ( $is_dev ? false : SCRIPTS_HASH ));

  }

  /**
   * Adds a script
   *
   * @param [string] $name
   * @return void
   */
  function enqueue_script( $name, $dependencies = array() ) {

    $is_dev = $this->has_dev_file($name);

    wp_enqueue_script( $this->get_name($name) ,
      $this->get_js($name ),
      $dependencies,
      ( $is_dev ? false : SCRIPTS_HASH ),
      ( $is_dev ? false : true )
    );

  }


  /**
   * Add async and defer to javascripts
   *
   * @param [string] $url
   * @return void
   */
  function async_js( $url ){
    if ( is_admin() ) return $url; // Don't defer admin
    if ( FALSE === strpos( $url, '.js' ) ) return $url; // Don't defer non .js files
    if ( strpos( $url, 'typekit' ) ) return $url; // Don't defer typekit
    if ( strpos( $url, 'ampproject' ) ) return $url; // Don't defer ampproject
    if ( strpos( $url, 'jwplatform' ) ) return $url;
    if ( strpos( $url, 'jquery' ) ) return $url;
    return "$url' async='async";
  }

  /**
   * Theme default critical styles
   *
   * @return void
   */
  function add_theme_critical_styles() {

    $file = $this->get_scripts_directory().($this->template_critical_prefix ? $this->template_critical_prefix."-critical.min.css" : 'critical.min.css');

    if(!file_exists( $file )){
      if($this->template_critical_prefix) $file = 'critical.min.css'; //default to main
      if(!file_exists( $file )) return;
    }

    $this->has_critical = true;

    $critical_name = (
      $this->template_critical_prefix != ''
      ? "$this->template_critical_prefix-critical-styles"
      : 'critical-styles'
    );

    $file_content = @file_get_contents( $file );
    $filtered_css = str_replace( '../../assets/fonts', '/wp-content/themes/wellgood-2016/assets/fonts', $file_content );

    echo "<style type=\"text/css\" title=\"$critical_name\" id='critical-css'>$filtered_css</style>";
  }

  /**
   * Remove theme critical styles to avoid conflicts
   *
   * @return void
   */
  function remove_theme_critical_styles() {

    if(!$this->has_critical) return;

    echo "
      <script type=\"text/javascript\">
        window.onload = function() {
          setTimeout(function(){
            var elem = document.querySelector('#critical-css');
            if(elem) elem.parentNode.removeChild(elem);
          },0)
        };
      </script>
    ";
  }

  /*
  *
  * Tailwind utilities as critical injected
  *
  */
  function add_theme_critical_vendor_styles(){

    if(is_dev() || !in_array('vendor',$this->theme_scripts_css)) return;

    $file = $this->get_css('vendor', true);
    if(!file_exists( $file )) return;

    unset($this->theme_scripts_css[array_search('vendor',$this->theme_scripts_css)]);

    $file_content = @file_get_contents($file);
    $filtered_css = str_replace( '../../assets/fonts', '/wp-content/themes/wellgood-2016/assets/fonts', $file_content );

    echo "<style type=\"text/css\" title=\"vendor-critical\">$filtered_css</style>";
  }


  /**
   * Template has main theme scripts
   *
   * @return boolean
   */
  function has_main_script($script = 'js'){
    return (
      !$this->template_prefix || empty( $this->template_prefix ) ||
      (
        ($this->template_prefix !== 'summer' ) &&
        ($this->template_prefix !== 'product-guide' || $script == 'js' )
      )
    );
  }

  /**
   * Theme main scripts (legacy)
   *
   * @return void
   */
  function prepare_legacy_theme_scripts(){
    
    if(!$this->is_defined_theme) {

      if ( ! empty( $this->template_prefix ) ) :

        array_unshift($this->theme_scripts_js, $this->template_prefix);
        array_unshift($this->theme_scripts_css, $this->template_prefix);

      else:

        $this->template_prefix = null;

      endif;

      if($this->has_main_script('js')) array_unshift($this->theme_scripts_js, 'main');
      if($this->has_main_script('css')) array_unshift($this->theme_scripts_css, 'main');

    }

  }

  /**
   * Theme vendor
   *
   * @return void
   */
  function prepare_vendor(){

    if(!in_array("vendor", $this->theme_scripts_css) && array_intersect($this->need_vendor, $this->theme_scripts_css)) $this->theme_scripts_css[] = 'vendor';
    if(!in_array("vendor", $this->theme_scripts_js) && array_intersect($this->need_vendor, $this->theme_scripts_js)) $this->theme_scripts_js[] = 'vendor';

  }

  /**
   * Prepare theme main scripts
   *
   * @return void
   */
  function prepare_theme_scripts() {

    // Legacy
    $this->set_theme_prefix();
    $this->prepare_legacy_theme_scripts();

    // Legacy
    $this->prepare_vendor();

  }

  /**
   * Theme main scripts
   *
   * @return void
   */
  function enqueue_theme_scripts() {

    // Styles
    foreach($this->theme_scripts_css as $name) $this->enqueue_style( $name );

    // Javascript
    foreach($this->theme_scripts_js as $name) $this->enqueue_script( $name );

    // Localize avascript
    $this->localize_theme( $this->theme_scripts_js[0] );
  }

  /**
   * Localize Theme (Global public js vars)
   *
   * @return void
   */
  function localize_theme($name = 'main') {

    $name = $this->get_name($name);
    
    // NOTE: This is for rating – should have some field that enables ratings to check for here
    wp_localize_script( $name, 'wpApiSettings', array(
      'root' => esc_url_raw( rest_url() ),
      'nonce' => wp_create_nonce( 'wp_rest' )
      ) );

    wp_localize_script( $name, 'NODE_ENV', NODE_ENV);

    wp_localize_script( $name, 'locationHub', array(
      'url' => $this->get_js('location-hub'),
    ) );
  }

  /**
   * Recipe Chicory Script
   *
   * @return void
   */
  function enqueue_recipe_chicory() {
    global $post;

    if ( is_singular( 'recipe' ) && get_field( 'enable_chicory', $post ) ) :
      wp_enqueue_script( 'chicory',
        '//www.chicoryapp.com/widget_v2/',
        array(),
        false,
        true
      );
    endif;
  }

  /**
   * Check for any <script></script> in content and make
   * sure it is not wrapped in <p></p>
   *
   * @param [type] $content
   * @return void
   */
  function filter_ptags_on_scripts( $content ) {
    return preg_replace( '/<p>\s*(<script.*>*.<\/script>)\s*<\/p>/iU', '\1', $content );
  }

  /**
   * Comscore inline js
   *
   * @return void
   */
  function inject_comscore() {
    ?>
    <!-- comScore Tag -->
    <script>
      var _comscore = _comscore || [];
      _comscore.push({ c1: "2", c2: "19765212" });
      (function() {
        var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
        s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
        el.parentNode.insertBefore(s, el);
      })();
    </script>
    <noscript>
      <img src="http://b.scorecardresearch.com/p?c1=2&c2=19765212&cv=2.0&cj=1" />
    </noscript>
    <?php
  }

  static function get_enqueued_scripts(){

      $result = array();

      // Print all loaded Scripts
      global $wp_scripts;
      foreach( $wp_scripts->queue as $script ) :
        $result['scripts'][] =  $wp_scripts->registered[$script]->handle;
      endforeach;

      // Print all loaded Styles (CSS)
      global $wp_styles;
      foreach( $wp_styles->queue as $style ) :
        $result['styles'][] =  $wp_styles->registered[$style]->handle;
      endforeach;

      return $result;
  }

  /**
  * Dequeue scripts
  */
  function dequeue_scripts() {

    wp_dequeue_style( 'wp-block-library');
    if ( !is_admin() ) wp_deregister_script('jquery');

  }
}
