<?php

namespace WG\API\Hooks;
use PHPHtmlParser\Dom;

class Save_Post_Dom_Parser {

  /*
  *
  *  Actions and filters
  *
  */

  public function __construct(){
    add_filter('content_save_pre', array($this, 'filter_content_save_pre'), 10, 1);
    add_action('save_post_async', array($this, 'on_save_post_async'), 10, 1);
  }


  /** 
  *
  *  Filter: content_save_pre
  */
  public function filter_content_save_pre($content){
    global $post;
    if(!is_object($post) || $content === '' || !$content) return $content;
    $HtmlDomParser = new Dom();
    $dom = $HtmlDomParser->loadStr($content);
    if($dom) $content = apply_filters( 'content_dom', $content, $dom );
    $post->content = $content;
    if(is_local()) $actions = array('save_post_dom_async', 'save_post_dom');
    else $actions = 'save_post_dom';
    $this->parse_dom_on_save($post, $actions, $dom);
    return $content;
  }


   /** 
  *
  *  Action: save_post_async
  */
  public function on_save_post_async($post){
    if(is_local() || !is_object($post)) return;
    $this->parse_dom_on_save($post, 'save_post_dom_async');
  }

   /** 
  *
  *  Parses Post DOM
  */
  function parse_dom_on_save($post, $actions = 'save_post_dom', $dom = false){

    remove_action('save_post_async', array($this, 'on_save_post_async'));

     //Check it's not an auto save routine
     if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    if(!$post) global $post;

    if(is_numeric($post)) $post = get_post($post);
 
    if(!$dom) $dom = $this->get_post_dom($post);

    if(!is_bool($dom)) {
      if(is_array($actions)){
        foreach($actions as $action) {
          do_action( $action,  $dom, $post );
        }
      } else {
        do_action( $actions,  $dom, $post );
      }
      wp_remove_object_terms($post->ID, 'parsing-error--no-dom', 'dev_tag');
    } else if(is_object($post) && $post->ID) {
      wp_set_object_terms($post->ID, 'parsing-error--no-dom', 'dev_tag', true);
    }
  }

  /** 
  *
  *  Get Post DOM
  */

  public function get_post_dom($post = false){
    if(!$post) {
      global $post;
    }
    if(!$post->post_content ) return false;
    $dom = new Dom();
    return $dom->loadStr($post->post_content);
  }
}
