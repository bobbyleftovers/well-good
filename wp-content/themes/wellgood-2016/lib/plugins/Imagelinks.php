<?php

namespace WG\Plugins;

use WG\Settings\Scripts;

class Imagelinks {

  function __construct() {
    add_filter('the_content',array($this,'imagelinks_add_wrapper'), 10, 1);
    add_action( 'wp_enqueue_scripts', array($this,'deregister_script'), 999999 );
  }

  /**
   * Add wrapper around instance of [imagelinks] shortcode by filtering the content
   * @link https://developer.wordpress.org/reference/functions/get_shortcode_regex/
   */

  function imagelinks_add_wrapper($content){
    global $post;
    $pattern = '/\[imagelinks(.*?)\]/';
    $shortcodes_found = preg_match_all($pattern, $content, $matches);

    if ($shortcodes_found && !empty($matches[0]))
    {
      foreach( $matches[0] as $key => $match ){
        $wrapped = "<div data-module-init=\"imagelinks\">$match</div>";
        $content = str_replace($match, $wrapped, $content);
      }
    }

    return $content;
  }

  /**
   * Trigger asynchronous stylesheets
   */
  function defer_style_filter($html, $handle, $href) {
      $handles = array('imagelinks_imagelinks');
      if( in_array( $handle, $handles ) ) {
        $deferred = str_replace( "href='" . $href . "'", 'href="#" onload="this.href=\'' . $href . '\'"
    ', $html );
        $deferred .= '<noscript>' . $html . '</noscript>';
        return $deferred;
      }
      return $html;
    }

    /**
     * Deregister jquery.imagelinks.js (bundled in main build)
    */
    function deregister_script() {
      wp_deregister_script ( 'imagelinks-imagelinks' );
    }

}
