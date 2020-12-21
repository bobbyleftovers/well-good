<?php

namespace WG\Settings;
use WG\Settings\Brrl_Gutenberg;

class Gutenberg extends Brrl_Gutenberg {

  

  /**
  *
  *  Gutenberg features supported
  *
  *  @var array
  */
  public $theme_supports = array(
    'align-wide'
  );

  /**
  *
  *  Extended styles
  *
  *  @var array
  */
  public $extended_styles = array(
    /*'core/image' => array(
      array(
        'name'         => 'triangle',
        'label'        => 'Triangle'
      )
    )*/
  );

  /**
  *
  *  Allow only this default block styles
  * (extended styles will be automatically included)
  *
  *  @var array
  */
  public $allowed_styles = array(
    /* 'core/image' => array(
      'default',
      'circle-mask'
    ) */
  );

  /**
  *
  *  Add hooks
  *
  */
  public function __construct() {

    set_class_config_vars($this, ['gutenberg/acf-blocks-registration', 'gutenberg/allowed-blocks', 'gutenberg/editor-exclusions']);

    $this->excluded_page_ids = array(
      get_option( 'page_on_front' )
    );

    add_filter('brrl_render_block_html_preview', array($this, 'theme_wrapper'), 10, 1);
    
    add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets') );

    parent::__construct();

  }

  /**
  *
  *  Wrap block and content with .theme-main-2020 class
  *
  */
  public function theme_wrapper($content){
    //if(!$block['is_preview']) return $html;
    return "<div class='theme-main-2020'>$content</div>";
  }

  /**
  * Enqueue block editor JavaScript and CSS
  */
  function enqueue_block_editor_assets() {

    theme_enqueue_bundle(
      'vendor'
    );
    
    theme_enqueue_bundle(
      'gutenberg-editor', 
      [ 'wp-i18n', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-api' ]
    );

  }
}
