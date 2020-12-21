<?php

namespace WG\Settings;

abstract class Brrl_Gutenberg {

  /**
  *
  *  Allowed only blocks
  *
  *  @var array
  */
  public $allowed_blocks;

  /**
  *
  *  Allowed only blocks by post type
  *
  *  @var array
  */
  public $allowed_blocks_by_post_type = array();

  /**
  *
  *  Allowed only blocks by page template
  *
  *  @var array
  */

  public $allowed_blocks_by_page_template = array();

  /**
  *
  *  Gutenberg features supported
  *
  *  @var array
  */
  public $theme_supports;

  /**
  *
  *  ACF blocks
  *
  *  @var array
  */
  public $acf_blocks;

  /**
  *
  *  Extended styles
  *
  *  @var array
  */
  public $extended_styles;
  /**
  *
  *  Allow only this default block styles
  * (extended styles will be automatically included)
  *
  *  @var array
  */
  public $allowed_styles;

  /**
  *
  *  Remove these page templates from editing with Gutenberg
  *
  *  @var array
  */
  public $excluded_page_templates;


  /**
  *
  *  Only these page templates can be edited on Gutenberg
  *
  *  @var array
  */
  public $include_only_page_templates;

  /**
  *
  *  Remove these post types from editing with Gutenberg
  *
  *  @var array
  */
  public $excluded_post_types;


  /**
  *
  *  Only these posts types can be edited on Gutenberg
  *
  *  @var array
  */
  public $include_only_post_types;


  /**
  *
  *  Remove these page ids from editing with Gutenberg
  *
  *  @var array
  */
  public $excluded_page_ids;

  /**
  *
  *  Block counter
  *
  *  @var number
  */
  static $private_counter = 0;
  static $total_blocks = 0;
  static $is_first = true;


  /**
  *
  *  Components Root Path
  *
  */
  static public $modules_dir = '/modules';

  /**
  *
  *  Allow columns layout
  *
  *  @var array
  */
  static public $parse_colums = false;

  /**
  *
  *  Add hooks
  *
  */
  public function __construct() {

    add_action( 'acf/init', array($this,'register_acf_block_types'));
    add_action( 'init', array($this,'add_theme_support') );
    add_filter( 'allowed_block_types', array($this,'allowed_block_types'), 10, 2 );
    add_action( 'admin_init', array($this,'extend_blocks_styles') );
    add_action( 'admin_footer', array($this,'unregister_blocks_styles'));
    add_filter( 'gutenberg_can_edit_post_type', array($this,'post_disable_gutenberg'), 10, 2 );
    add_filter( 'use_block_editor_for_post_type', array($this,'post_disable_gutenberg'), 10, 2 );
    add_action( 'admin_head', array($this,'page_disable_classic_editor') );

  }

  /**
  *
  *  Equivalent to the_content() WP function
  *  Renders Brrl modules instead
  *
  */
  public static function the_content($post = false, $args = false){
    if(!$post) global $post;
    echo self::get_the_content($post, $args);
  }

  public static function get_the_content($post = false, $args = false, $blocks = false){
    if(!$post) global $post;
    if(!has_blocks( $post->ID )) return $post->post_content;
    if($args){
      // Pass data to gutenberg blocks
      add_filter('brrl_render_block', function( array $block) use ($args) {
        return array_merge($args, $block);
      });
    }
    return apply_filters('the_content_gutenberg',  self::get_blocks_html($post->post_content, $blocks));
  }

  /*
  *
  * brrl_get_blocks()
  *
  */

  public static function get_blocks($post = false){
    if(!$post) global $post;
    if(!has_blocks( $post->ID )) return null;
    return self::parse_blocks($post->post_content);
  }

  /**
  *
  *  ACF Gutenberg Blocks
  *
  */
  function register_acf_block_types(){
    if(!function_exists('acf_register_block_type') || !isset($this->acf_blocks) || !$this->acf_blocks) return;

    foreach($this->acf_blocks as $block){

      $block['title'] = __($block['title']);
      $block['description'] = __($block['description']);

      $block['render_callback'] = array($this,'render_acf_block');

      acf_register_block_type($block);
    }
  }

  /**
  *
  *  Extends blocks styles
  *
  */
  function extend_blocks_styles(){
    if(!isset($this->extended_styles) || !$this->extended_styles) return;

    foreach($this->extended_styles as $blockName => $styles){

      foreach($styles as $style){

        $style['label'] = __($style['label']);

        register_block_style(
          $blockName,
          $style
        );

      }
    }

  }

  /**
  *
  *  Remove block styles
  *
  */
  function unregister_blocks_styles(){

    if(!is_admin() || !isset($this->allowed_styles) || !is_array($this->allowed_styles)) return;

    $allowed_styles = $this->allowed_styles;

    if(isset($this->extended_styles) && is_array($this->extended_styles)){

      foreach($this->extended_styles as $blockName => $block_styles){

        if(!isset($allowed_styles[$blockName])) $allowed_styles[$blockName] = array();

        foreach($block_styles as $style){

          $allowed_styles[$blockName][] = $style['name'];

        }

      }

    }

    $allowed_blocks = 'var allowed_blocks = {';

    foreach($this->allowed_styles as $blockName => $styles){

      $allowed_blocks .= '"'.$blockName.'":[';

      $i = 0;

      foreach($styles as $style){
        if($i) $allowed_blocks.=',';
        $allowed_blocks .= '"'.$style.'"';
        $i++;
      }

      $allowed_blocks .= '],';
    }

    $allowed_blocks .= '}';

    ?>

    <script>
      jQuery(document).ready( function($){
        if(typeof wp === 'undefined' || typeof wp.hooks === 'undefined' || typeof wp.hooks.addFilter === 'undefined') return;
        wp.hooks.addFilter('blocks.registerBlockType', 'brrl/filters', function(block){

          <?= $allowed_blocks ?>;

          var filteredStyles = [];

          if(allowed_blocks[block.name]){

            for (var i = 0; i < block.styles.length; i++) {

              if(allowed_blocks[block.name].includes(block.styles[i].name)){

                filteredStyles.push(block.styles[i]);

              }
            }

          }

          block.styles = filteredStyles;

        return block; })
      })
    </script>

    <?php

  }

  /**
  *
  *  Filters allowed blocks
  *
  */
  function allowed_block_types( $allowed_blocks, $post ) {

    // Filter by page template
    if($post->post_type === 'page') $page_template = get_page_template_slug( $post );
    if($post->post_type === 'page' ){
      $filter = false;
      if($post->post_parent && isset($this->allowed_blocks_by_page_template[$page_template.':child'])): 
        $filter = $this->allowed_blocks_by_page_template[$page_template.':child'];
      elseif(isset($this->allowed_blocks_by_page_template[$page_template.':parent'])): 
        $filter = $this->allowed_blocks_by_page_template[$page_template.':parent'];
      elseif(isset($this->allowed_blocks_by_page_template[$page_template])): 
        $filter = $this->allowed_blocks_by_page_template[$page_template];
      endif;
      if($filter) $allowed_blocks = apply_filters('allowed_blocks_by_page_template', $filter, $post);
    // Filter by post type
    } else if(isset($this->allowed_blocks_by_post_type[$post->post_type])) {
      $allowed_blocks = apply_filters('allowed_blocks_by_post_type', $this->allowed_blocks_by_post_type[$post->post_type],$post);
    
    // Global allowed posts
    } else if (isset($this->allowed_blocks) && $this->allowed_blocks) {
      $allowed_blocks = $this->allowed_blocks;
    }

    $blocks = apply_filters('allowed_blocks_by_post_type/'.$post->post_type, $allowed_blocks, $post);
    if($post->post_type === 'page') $allowed_blocks = apply_filters('allowed_blocks_by_page_template/'.$page_template, $allowed_blocks, $post);

    return $allowed_blocks;

  }

  /**
  *
  *  Adds Theme support
  *
  */
  function add_theme_support() {

    if(!isset($this->theme_supports) || !$this->theme_supports) return;

    foreach($this->theme_supports as $feature){
      add_theme_support( $feature );
    }
  }

  /**
  *
  *  Checks if block has a specific style
  *
  */
  static function is_style($block, $style){
    if(!isset($block['attrs']['className']) || strpos($block['attrs']['className'],'is-style-'.$style) === false){
      return false;
    } else {
      return true;
    }
  }

  /**
  *
  *  Parse and render blocks
  *
  *  @return string
  */
  static function get_blocks_html($content, $blocks = false){

    if(!$blocks) $blocks = self::parse_blocks($content);

    return self::render_blocks($blocks);

  }

  /**
  *
  *  Parse and render blocks
  *
  *  @return string
  */
  static function parse_blocks($content){

    $blocks = parse_blocks($content);

    $blocks = self::prepare_blocks($blocks);

    return $blocks;

  }

  /**
  *
  *  Count blocks
  *
  */
  static function count_blocks($blocks){
    $counter = 0;
    foreach($blocks as $block) {
      $counter++;
      if(isset($block['innerBlocks']) && sizeof($block['innerBlocks']) > 0 ) {
        $counter += self::count_blocks($block['innerBlocks']);
      }
    }
    return $counter;
  }

  /**
  *
  *  Prepare blocks
  *
  */
  static function prepare_blocks($blocks){

    //Hydrate reusable
    $blocks = self::hydrate_reusable_blocks($blocks);

    // Prepare columns layouts
    if(self::$parse_colums) {
      // Set columns width
      $blocks = self::prepare_columns_width($blocks);
      
      // Merge columns
      $blocks = self::prepare_columns_merge($blocks);
    }

    // Count blocks
    self::$total_blocks += self::count_blocks($blocks);

    //return
    return $blocks;
  }

  /**
  *
  *  Hydrate reusable blocks
  *
  */

  static function hydrate_reusable_blocks($blocks){

    $parsed_blocks = array();

    foreach($blocks as $key => $block){

      $is_reusable = false;

      // Fetch reusable
      if($block['blockName'] === 'core/block' && isset($block['attrs']) && isset($block['attrs']['ref'])) {
        $is_reusable = true;
        $post = get_post( $block['attrs']['ref'] );
        $block = self::parse_blocks($post->post_content);
      }

      //Recursive
      if(isset($block['innerBlocks']) && sizeof($block['innerBlocks']) > 0 ) {
        $block['innerBlocks'] = self::hydrate_reusable_blocks($block['innerBlocks']);
      }

      // Append block or blocks
      if($is_reusable && !isset($block['blockName'])){
        $parsed_blocks = array_merge($parsed_blocks, $block);
      } else if($block['blockName'] !== null) {
        $parsed_blocks[] = $block;
      }
    }

    return $parsed_blocks;
  }

  /**
  *
  *  Set col widths
  *
  */
  static function prepare_columns_width($blocks){
    foreach($blocks as &$block){

      //Block null
      if(! $block['blockName'] ) continue;

      //Core/columns
      if( $block['blockName'] === 'core/columns' ){
        $width = 100;
        $flex = 0;

        foreach($block['innerBlocks'] as $innerBlock){
          if(!isset($innerBlock['attrs'])) $innerBlock['attrs'] = array();
          if(isset($innerBlock['attrs']['width'])){
            $width -= $innerBlock['attrs']['width'];
          } else {
            $flex++;
          }
        }

        if($width > 0){
          if($flex > 0) $width = (float) $width / (int) $flex;
          foreach($block['innerBlocks'] as $key => $innerBlock){
            if(!isset($innerBlock['attrs']['width'])){
              $block['innerBlocks'][$key]['attrs']['width'] = $width;
            }
          }
        }
      }

      //Recursive
      if(isset($block['innerBlocks']) && sizeof($block['innerBlocks']) > 0 ) {
        $block['innerBlocks'] = self::prepare_columns_width($block['innerBlocks']);
      }
    }

    return $blocks;
  }

  /**
  *
  *  Merge cols
  *
  */
  static function prepare_columns_merge($blocks){
    $blocks_new = array();
    $is_columns = false;
    $i = -1;
    foreach($blocks as $block){
      if(!$block['blockName']) continue;
      if($is_columns && $block['blockName'] === 'core/columns'){
        $blocks_new[$i]['innerBlocks'] = array_merge($blocks_new[$i]['innerBlocks'],$block['innerBlocks']);
      } else {
        $blocks_new[] = $block;
        if($block['blockName'] === 'core/columns'){
          $is_columns = true;
        } else {
          $is_columns = false;
        }
        $i++;
      }
    }
    foreach($blocks_new as &$block){
      if(isset($block['innerBlocks']) && sizeof($block['innerBlocks']) > 0 ) {
        $block['innerBlocks'] = self::prepare_columns_merge($block['innerBlocks']);
      }
    }
    return $blocks_new;
  }


  /**
  *
  *  Render an array of blocks into an html string
  *
  *  @return object
  */
  static function render_blocks(&$blocks, $parentBlock = false){
    $html = '';

    foreach($blocks as $key => &$block){

      if(!isset($block['blockName']) && (!$block['innerHTML'] || $block['innerHTML'] == '') ) {
        self::$total_blocks--;
        continue;
      }

      // Previous block
      if($key > 0){
        $block['prev'] = $blocks[$key-1];
      } else {
        $block['prev'] = false;
      }

      // Next Block
      if(isset($blocks[$key+1])){
        $block['next'] = $blocks[$key+1];
      } else {
        $block['next'] = false;
      }

      if(!isset($block['blockName'])) $block['blockName'] = 'core/classic-editor';

      $block['parentBlock'] = false;
      if($parentBlock) {
        $block['parentBlock'] = $parentBlock;
        unset($block['parentBlock']['innerBlocks']);
      }

      $block = self::render_block($block);

      $html .= $block['innerHTML'];
    }

    return $html;
  }

  /**
  *
  *  Render block
  *
  *  @return object  block object with innerHTML rendered as Brrl modules
  *
  */
  static function render_block(&$block, $content = '', $is_preview = false, $post_id = 0 ){

    // Vars
    $block['blockName'] = $block['blockName'] ?? $block['name'];
    $block['is_preview'] = $is_preview;
    $block['is_editor'] = $is_preview;
    $is_acf_block = (strpos($block['blockName'], 'acf/') !== false);
    $block['blockName'] = apply_filters('brrl_render_block_name',$block['blockName']);
    $name = apply_filters('brrl_render_block_name_namespace', explode("/",$block['blockName']));
    $namespace = apply_filters('brrl_render_block_namespace', $name[0], $name);
    $name = apply_filters('brrl_render_block_name', $name[1], $name);
    $block['block_class'] = str_replace('/','-',$block['blockName']);

    // Set ACF meta
    if($is_acf_block && !$is_preview) {
      $id = $block['attrs']['id'] ?? $block['id'];
      $data = $block['attrs']['data'] ?? ($block['data'] ?? array());
      acf_setup_meta( $data, $id , true );
    }

    // ACF Attrs
    if($is_acf_block) {
      self::hydrate_acf_attrs($block);
    }

    // Nested blocks
    if( isset($block['innerBlocks']) && sizeof($block['innerBlocks']) > 0 ){
      $block['innerHTML'] = self::render_blocks($block['innerBlocks'], $block);
    }

    // Block data
    global $post;
    if($post && !isset($block['post'])) $block['post'] = $post;
    self::$private_counter++;
    $block = array_merge($block, $block['attrs']);
    $block['block_counter'] = self::$private_counter;
    $block['is_first'] = self::$is_first;
    self::$is_first = false;
    $block['is_last'] = (self::$private_counter === self::$total_blocks);
    $block['block'] =  $block;

    $block = apply_filters('brrl_render_block', $block);

    // Render module
    $root = static::$modules_dir;
    if(!file_exists( TEMPLATEPATH . "$root/$namespace/$name/$name.php" ) && !($is_preview && file_exists( TEMPLATEPATH . "$root/$namespace/$name/$name.editor.php" ))){
      $block['innerHTML'] = do_shortcode($block['innerHTML']);
    } else {
      $mod = "$namespace/$name";
      if($is_preview && file_exists( TEMPLATEPATH . "$root/$namespace/$name/$name.editor.php")) $mod = "$mod.editor";
      $block['innerHTML'] = brrl_get_module($mod, $block);
    }

    $block['innerHTML'] = strip_shortcodes($block['innerHTML']);

    $block['innerHTML'] = apply_filters('brrl_render_block_html', $block['innerHTML'], $block);
    if($is_preview) $block['innerHTML'] = apply_filters('brrl_render_block_html_preview', $block['innerHTML'], $block);

    // Reset ACF meta
    if($is_acf_block && !$is_preview) {
      acf_reset_meta( $block['attrs']['id'] ?? $block['id'] );
    }

    // Return hydrated block
    return $block;
  }

  /**
  *
  *  Render ACF block
  *
  *  Used as callback for acf_register_block_type()
  *
  *  @return object  block object
  *
  */
  static function render_acf_block($block, $content = '', $is_preview = false, $post_id = 0 ){

    // Get hydrated block
    $block = self::render_block($block, $content, $is_preview, $post_id);

    // Print on Admin preview
    if($is_preview) {
      echo $block['innerHTML'];
      ?>
      <script>
        setTimeout(function(){
          console.log('ACF block rendered');
        },10);
      </script>
      <?php
    }

    return $block;
  }

  /**
  *
  *  Hydrate ACF fields as Block attrs
  *
  *  @return object  block object
  *
  */
  static function hydrate_acf_attrs(&$block){
    if(!isset($block['attrs'])) $block['attrs'] = array();

    $fields = get_fields();
    $fields = $fields ? $fields : array();

    $block['attrs'] = array_merge($block['attrs'], array_merge(
      $fields,
      array(
        'raw' => get_fields(null, false)
      )
    ));
    
    return $block;
  }

  /**
   * Disable Editor
   *
  **/

  /**
   * Disable by page template or id
   *
   */
  function page_disable_editor( $id = false ) {

    if( empty( $id ) )
      return false;

    $id = intval( $id );
    $template = get_page_template_slug( $id );

    //Include only
    if( isset($this->include_only_page_templates) && is_array($this->include_only_page_templates) && !in_array( $template, $this->include_only_page_templates )) return true;

    //Exclude
    return in_array( $id, $this->excluded_page_ids ?? array() ) || in_array( $template, $this->excluded_page_templates  ?? array() );
  }

  /**
   * Disable by post type
   *
   */
  function post_type_disable_editor( $post_type = false ) {

    if( empty( $post_type ) )
      return false;

    //Include only
    if( isset($this->include_only_post_types) && is_array($this->include_only_post_types) && !in_array( $post_type, $this->include_only_post_types )) return true;

    //Exclude
    return in_array( $post_type, $this->excluded_post_types  ?? array() );
  }

  /**
   * Disable Gutenberg by post type && page template
   *
   */
  function post_disable_gutenberg( $can_edit, $post_type) {

    if( $this->post_type_disable_editor( $post_type ) )
      $can_edit = false;

    if($post_type === 'page'){
      if(isset($_GET['post'])){
        if ( $this->page_disable_editor($_GET['post']) )
          $can_edit = false;
      }
      elseif (isset($this->include_only_page_templates) && is_array($this->include_only_page_templates)){
        $can_edit = false;
      }
    }

    return $can_edit;

  }

  /**
   * Disable Classic Editor by template
   *
   */
  function page_disable_classic_editor() {

    $screen = get_current_screen();
    if( 'page' !== $screen->id || ! isset( $_GET['post']) )
      return;

    if( $this->page_disable_editor( $_GET['post'] ) ) {
      remove_post_type_support( 'page', 'editor' );
    }

  }

}
