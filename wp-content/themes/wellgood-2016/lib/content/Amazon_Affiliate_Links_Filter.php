<?php

namespace WG\Content;
use PHPHtmlParser\Dom;

class Amazon_Affiliate_Links_Filter {

  /*
  *
  *  Actions and filters
  *
  */
  function __construct(){
    add_filter( 'content_dom', array($this, 'filter_dom_content'), 10,2);
    add_action( 'save_post_imgl_item', array($this, 'filter_image_links'),10,1);
    add_action( 'acf/update_value/type=url', array($this, 'filter_url'), 10,1);
    add_action( 'acf/update_value/type=wysiwyg', array($this, 'filter_dom'), 10,1);
  }

  /*
  *
  *  Checks if post has tag 'branded'
  *
  */
  static function post_is_branded($the_post = false){
    if(!$the_post){
      global $post;
      $the_post = $post;
    }
    if($the_post && is_object($the_post) && $the_post->post_type == 'post' && has_tag('branded',$the_post)) return true;
    return false;
  }

  /*
  *
  *  Parses post_content DOM links (works for any post type, including recipes)
  *
  */
  function filter_dom_content($content, $dom){
    if(self::post_is_branded()) return $content;

    foreach($dom->find('a') as &$a){
      //adding full attribute and quotes ensures we won't replace partial equivalent urls
      $href = "href=\"".self::add_tag_to_href($a->href)."\"";
      if(substr($a->href, 0, 2) === '\"' || substr($iframe->src, 0, 2) === "\'") $original = 'href='.$a->href;
      else $original = 'href="'.$a->href.'"';
      $content = str_replace($original,$href,$content);
      $content = str_replace("href='".$a->href."'",$href,$content);
    }
    return $content;
  }

  /*
  *
  *  Parses Shop Links, Image link hotspot_cta_link,...
  *
  */
  function filter_url($value){
    if(self::post_is_branded()) return;
    $value = self::add_tag_to_href($value);
    return $value;
  }

  /*
  *
  *  Parses wyiwyg acf fields
  *
  */
  function filter_dom($value){
    if(self::post_is_branded()) return;
    $HtmlDomParser = new Dom();
    $dom = $HtmlDomParser->loadStr($value);
    if(!$dom) return $value;
    return self::filter_dom_content($value, $dom);
  }


  /*
  *
  *  Parses imagelinks meta
  *
  */
  function filter_image_links($postID){
    foreach(get_post_meta($postID, 'imgl-meta-imagelinks-cfg') as $meta_item){
      $meta_item_new = unserialize($meta_item);
      foreach ($meta_item_new->hotSpots as &$hotspot){
        $hotspot->link = self::add_tag_to_href($hotspot->link);
      }
      update_post_meta( $postID, 'imgl-meta-imagelinks-cfg',  serialize($meta_item_new) , $meta_item );
    }

    foreach(get_post_meta($postID, 'imgl-meta-ui-cfg') as $meta_item){
      $meta_item_new = unserialize($meta_item);
      foreach ($meta_item_new->hotspots as &$hotspot){
        $hotspot->config->link = self::add_tag_to_href($hotspot->config->link);
      }
      update_post_meta( $postID, 'imgl-meta-ui-cfg',  serialize($meta_item_new) , $meta_item );
    }
  }

  /*
  *
  *  Adds query tag
  *
  */
  function add_tag_to_href($href){
    $href = trim($href, '\"');
    $href = trim($href, "\'");
    if (!preg_match("~^(?:f|ht)tps?://~i", $href)) {
      $hrefWithScheme = "https://" . $href;
    }  else {
      $hrefWithScheme = $href;
    }
    $url = parse_url($hrefWithScheme);
    if(!isset($url['host']) || ($url['host'] !== 'www.amazon.com' && $url['host'] !== 'amazon.com')) {
      return $href;
    }
    if(isset($url['query'])){
      parse_str($url['query'], $query);
    } else {
      $query = array();
    }
    if(!isset($query['tag'])) $query['tag'] = get_field('amazon_affiliate_links_tag', 'option') ?: 'wellgoodauto-20';
    $url['query'] = '';
    foreach($query as $key=>$val) $url['query'] .= $key.'='.$val.'&';
    $url['query'] = rtrim($url['query'],"&");
    $href = self::build_url($url);
    return $href;
  }

  /*
  *
  *  Build URL
  *
  */
  function build_url(array $parts) {
    return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
        ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
        (isset($parts['user']) ? "{$parts['user']}" : '') .
        (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
        (isset($parts['user']) ? '@' : '') .
        (isset($parts['host']) ? "{$parts['host']}" : '') .
        (isset($parts['port']) ? ":{$parts['port']}" : '') .
        (isset($parts['path']) ? "{$parts['path']}" : '') .
        (isset($parts['query']) ? "?{$parts['query']}" : '') .
        (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
  }
}
