<?php
/**
 * Datalayer
 * 
 * A data layer is an object that contains all of the 
 * information that you want to pass to Google Tag Manager
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */


namespace WG\Content;

class Datalayer {

  function __construct() {
    add_filter('the_content',array($this,'add_datalayer_attributes'),10,1);
  }

  /**
   * Function to get data for datalayer and permutive
   * @param int ID - Post object ID
   * @return array - all available data
   */
  static function get_data( $id, $is_ajax ) {
    $current_page = get_page_type( $id, array( 'return_data' => TRUE ) );

    $data = array();

    $data['pagetype'] = $current_page['pagetype'];
    $data['content_name'] = $current_page['content_name'];

    // published
    $published_date = get_the_date( 'Y-m-d\TH:i:s.000\Z', $id );
    $data['published'] = $published_date;
    
    // updated
    $updated_date = get_the_modified_date( 'Y-m-d\TH:i:s.000\Z', $id );
    if ($published_date == $updated_date) :
      $data['updated'] = '';
    else :
      $data['updated'] = $updated_date;
    endif;
    
    // author
    $data['author'] = $current_page['object'] ? get_the_author_meta( 'display_name' , $current_page['object']->post_author ) : '';

    // adcats
    $data['adcats'] = self::get_data_adcat( $id );
    
    // vertical
    $hero_tag = get_field( 'hero_tag', $id );
    $vertical = get_vertical( $hero_tag );
    $data['vertical'] = $vertical ? $vertical : '';

    // tags
    $post_tags = array();
    $tag_1 = get_field( 'tag_1', $id );
    $tag_2 = get_field( 'tag_2', $id );
    if ( $tag_1 ) :
      array_push( $post_tags, $tag_1 );
    endif;
    if ( $tag_2 ) :
      array_push( $post_tags, $tag_2 );
    endif;
    $post_tags = array_map( function( $category_id ) {
      return get_category( $category_id );
    }, $post_tags );
    
    $ad_tags = ! empty( get_the_terms( $id, 'ad_tag' ) ) ? get_the_terms( $id, 'ad_tag' ) : array();
    $terms_backend_tags = ! empty( get_the_terms( $id, 'backend_tag' ) ) ? get_the_terms( $id, 'backend_tag' ) : array();
    $backend_tags = array_values( array_filter( $terms_backend_tags, function( $backend_tag ) {
      $term_tax = $backend_tag->taxonomy;
      $term_id = $backend_tag->term_id;

      $exposed = get_field( 'backend_tag_expose', "{$term_tax}_{$term_id}" );
      return $exposed;
    } ) );
    $terms_disclaimers = ( ! empty(get_the_terms( $id, 'disclaimer' ) ) ? get_the_terms( $id, 'disclaimer' ) : array() );
    
    if ( is_array( $terms_disclaimers ) ) :
      $disclaimers = array_values(array_filter($terms_disclaimers, function($disclaimer) {
        $term_tax = $disclaimer->taxonomy;
        $term_id = $disclaimer->term_id;
    
        $exposed = get_field('disclaimer_expose', "{$term_tax}_{$term_id}");
        return $exposed;
      }));
    else :
      $disclaimers = array();
    endif;

    $tags = array_map( function( $tag ) {
      return preg_replace( '/[\- ]/', '_', strtolower( $tag ) );
    }, array_values( array_unique( array_map( function( $tag ) {
      return strtolower( $tag->name );
    }, array_merge( $post_tags, $ad_tags, $backend_tags, $disclaimers ) ) ) ) );

    $data['tags'] = isset( $tags ) ? $tags : '';

    // user
    $data['user'] = array(
      'lg_uuid' => ''
    );
    if ( get_current_user_id() ) :
      $data['user']['user_id'] = strval(get_current_user_id());
    endif;

    return $data;
  }



  /**
   * Function to return info needed for the dataLayer
   * @param int ID - Post object ID
   * @param bool is_ajax - Whether or not the post is an ajax post query
   * @param int infinite_instance - Which infinite article we're on
   * @return array - all available dataLayer info
   */
  static function get_datalayer_data( $id = null, $type = 'standard', $is_ajax = false, $infinite_instance = null ) {
    $data = self::get_data( $id, $is_ajax );
    
    // category
    $hero_tag = get_field( 'hero_tag', $id );
    $data['category'] = $hero_tag ? preg_replace( '/[\- ]/', '_', strtolower( get_term( $hero_tag, 'category' )->name ) ) : '';

    // scroll
    $data['scroll'] = $infinite_instance ? (int) $infinite_instance : 0;

    // partners
    $partners = get_the_terms( $id, 'partners' );
    $data['partners'] = (!empty($partners) && is_array($partners)) ? implode(', ', array_map(function($partner) { return strtolower($partner->name); }, $partners)) : 'non-branded';

    // pageview_type
    if ( $type != 'amp' ) :
      $data['pageview_type'] = $infinite_instance == 0 ? $type : 'virtual';
    else :
      $data['pageview_type'] = 'AMP';
    endif;

    // campaign
    $campaigns = get_the_terms($id, 'campaigns');
    $data['campaigns'] = ! empty( $campaigns ) ? implode( ', ', array_map( function( $campaign ) { return strtolower( $campaign->name ); }, $campaigns ) ) : 'no-campaign';
    $data_array = array(
      'pagetype' => $data['pagetype'],
      'content_name' => $data['content_name'],
      'vertical' => $data['vertical'],
      'category' => $data['category'],
      'adcat1' => array_key_exists( 0, $data['adcats'] ) ? $data['adcats'][0] : '',
      'adcat2' => array_key_exists( 1, $data['adcats'] ) ? $data['adcats'][1] : '',
      'adcat3' => array_key_exists( 2, $data['adcats'] ) ? $data['adcats'][2] : '',
      'adcat4' => array_key_exists( 3, $data['adcats'] ) ? $data['adcats'][3] : '',
      'published' => $data['published'],
      'updated' => $data['updated'],
      'author' => $data['author'],
      'tags' => implode(', ', $data['tags']),
      'scroll' => $data['scroll'],
      'pageview_type' => $data['pageview_type'],
      'partner' => $data['partners'],
      'campaign' => $data['campaigns']
    ) + $data['user'];

    return apply_filters( 'gtm_data_layer', $data_array );
  }

  static function get_permutive_data( $id = null, $type = 'standard', $is_ajax = false, $infinite_instance = null ) {
    $data = self::get_data( $id, $is_ajax );
    
    // logged_in
    $data['user']['logged_in'] = is_user_logged_in() ? true : false;
    
    // scroll
    $data['scroll'] = $infinite_instance ? (int) $infinite_instance : 0;

    if ( $type == 'standard' ) :
      $data_array = array(
        'page' => array(
          'adcat1' => array_key_exists(0, $data['adcats']) ? $data['adcats'][0] : '',
          'adcat2' => array_key_exists(1, $data['adcats']) ? $data['adcats'][1] : '',
          'adcat3' => array_key_exists(2, $data['adcats']) ? $data['adcats'][2] : '',
          'adcat4' => array_key_exists(3, $data['adcats']) ? $data['adcats'][3] : '',
          'vertical' => $data['vertical'],
          'tags' => $data['tags'],
          'pagetype' => $data['pagetype'],
          'content_name' => $data['content_name'],
          'published' => $data['published'],
          'updated' => $data['updated'] != '' ? $data['updated'] : $data['published'],
          'author' => $data['author'],
          'scroll' => $data['scroll'],
          'pageview_type' => $infinite_instance == 0 ? $type : 'virtual',
          'user' => $data['user']
        )
      );
    elseif ( $type == 'amp' ) : 
      $data_array = array(
        'vars' => array(
          'namespace' => 'leafgroup',
          'key' => 'e4ecf9e0-0a2f-42d6-a720-8fff2402c221'
        ),
        'extraUrlParams' => array(
          'properties.adcat1' => array_key_exists(0, $data['adcats']) ? $data['adcats'][0] : '',
          'properties.adcat2' => array_key_exists(1, $data['adcats']) ? $data['adcats'][1] : '',
          'properties.adcat3' => array_key_exists(2, $data['adcats']) ? $data['adcats'][2] : '',
          'properties.adcat4' => array_key_exists(3, $data['adcats']) ? $data['adcats'][3] : '',
          'properties.vertical' => $data['vertical'],
          'properties.tags!list' => implode(',', $data['tags']),
          'properties.content_name' => $data['content_name'],
          'properties.pagetype' => $data['pagetype'],
          'properties.published' => $data['published'],
          'properties.updated' => $data['updated'],
          'properties.author' => $data['author'],
          'properties.pageview_type' => 'AMP',
          'properties.user.logged_in' => $data['user']['logged_in'],
          'properties.user.user_id' => array_key_exists('user_id', $data['user']) ? $data['user']['user_id'] : ''
        )
      );
    endif;
    
    return $data_array;
  }


  static function get_data_adcat( $id ) {
    $prev           = 0;
    $ordered_adcats = array();
    $adcats         = ! empty( get_the_terms( $id, 'ad_cat' ) ) ? get_the_terms( $id, 'ad_cat' ) : array();
    
    foreach ( $adcats as $adcat ) :
      $count = count( $ordered_adcats );
  
      $ordered_adcat_array = array_values(
        array_filter(
          $adcats,
          function( $cat ) use ( $prev ) {
            return $cat->parent === $prev;
          }
        )
      );
      if ( ! empty( $ordered_adcat_array ) ) :
        $ordered_adcat            = $ordered_adcat_array[0];
        $ordered_adcats[$count]   = $ordered_adcat->slug;
        $prev                     = $ordered_adcat->term_id;
      endif;
    endforeach;
  
    return $ordered_adcats;
  }

  /**
   * Adds a few relevant data attributes to all <a> tags within blog content
   * Used specifically to inform tracking information through GTM
   */
  function add_datalayer_attributes( $html ) {
    if ( is_feed() ) :
      return $html;
    endif;

    preg_match_all('/href=\".*?\"/', $html, $replacements);
    foreach ($replacements[0] as $replace) {
      $url = explode("=", $replace);
      for ($x = 2; $x < count($url); $x++) :
        $url[1] .= '=' . $url[$x];
      endfor;
      $url = trim($url[1], '"');
      $search_url = preg_quote($url, '/');
      
      $html = preg_replace("/<a href=\"$search_url\"/", "<a data-vars-event=\"body text\" data-vars-click-url=\"$url\" href=\"$url\"", $html);    	
    }
    return $html;
  }

}