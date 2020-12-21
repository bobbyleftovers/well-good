<?php
/**
 * Infinite Scroll
 *
 * Fetch and load posts below the current article
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */


namespace WG\API\REST;

use WG\API\REST\REST_Controller;

class Infinite_Scroll extends REST_Controller {

  /**
   * Routes
   */
  protected $routes = array(
    array(
      'route'     => '/infinite-scroll',
      'callback'  => 'add_next_scroll_post',
      'methods'   => array( 'GET', 'POST' )
    )
  );

  /**
   * Request response logic for infinite scroll.
   * If request is for a branded post, return branded post from option in that category.
   * Otherwise, page through recent posts in that category, and get_module for that post type.
   * @return array [$instance (int), $post_markup (html), $category_id (int), $branded_tag_id (int)];
   */
  static function add_next_scroll_post( $request ) {
    // Request variables
    $instance               = $request['instance'];
    $vertical               = $request['vertical'];
    $initial_post           = $request['initialPost'];
    $preset_post            = $request['presetPost'];
    $blacklisted_partners   = json_decode( $request['blacklistedPartners'] );
    $for_amp                = isset($request['amp']);
    $recent_posts           = [];

    // Post Variables
    $hero_tag               = get_field( 'hero_tag', $initial_post );

    if (!$hero_tag) {
      return [];
    }

    $top_level_category     = get_top_level_category( $hero_tag );
    $branded_tag            = get_term_by( 'slug', 'branded', 'post_tag' );
    $branded_backend_tag    = get_term_by( 'slug', 'branded', 'backend_tag' );
    $infinite_preset        = get_infinite_preset( $vertical, $initial_post );

    // General Query
    $tax_query = array(
      'relationship' => 'AND',
      array(
        'taxonomy'  => 'category',
        'field'     => 'term_id',
        'terms'     => array( $top_level_category ),
        'operator'  => 'IN'
      ),
      array(
        'taxonomy'  => 'post_tag',
        'field'     => 'term_id',
        'terms'     => array( $branded_tag->term_id ),
        'operator'  => 'NOT IN'
      ),
      array(
        'taxonomy'  => 'post_tag',
        'field'     => 'term_id',
        'terms'     => array( $branded_backend_tag->term_id ),
        'operator'  => 'NOT IN'
      )
    );

    if ( ! empty( $blacklisted_partners ) ) :
      $tax_query[] = array(
        'taxonomy'  => 'partners',
        'field'     => 'term_id',
        'terms'     => $blacklisted_partners,
        'operator'  => 'NOT IN'
      );
    endif;

    if ( $preset_post && intval( $instance ) === 1 ) :
      $post_id = $preset_post;
    else :
      $exclude = array( $initial_post );
      if ( $infinite_preset ) :
        $exclude[] = $preset_post;
      endif;

      $args = array(
        'post_type'       => array( 'post', 'recipe' ),
        'post__not_in'    => $exclude,
        'posts_per_page'  => 1,
        'paged'           => $instance,
        'tax_query'       => $tax_query
      );

      $recent_posts = get_posts( $args );
      if (empty($recent_posts)) {
        return [];
      }
      $post_id = $recent_posts[0]->ID;
    endif;

    $post_type = get_post_type( $post_id );
    if ($for_amp) return self::get_amp_payload(get_post($post_id));
    $post_markup = get_module( "$post_type-content", array(
      'post_id' => $post_id,
      'instance' =>  $instance
    ) );

    return array( $post_id, $post_markup );
  }

  public static function get_next_pages_amp ($post_id) {
    $hero_tag         = get_field( 'hero_tag', $post_id );
    $vertical         = get_vertical( $hero_tag );
    $blacklist = json_encode(get_field('infinite_scroll_blacklist', 'options'));
    $payload = [];
    $infinite_preset  = get_infinite_preset( $vertical, $post_id );
    $present_id = $post_id;

    if ($infinite_preset) {
      $present_id = $infinite_preset->ID;
    }

    for ($i = 1; $i <= 10; $i++) {
      $data =self::add_next_scroll_post([
        'instance' => $i,
        'initialPost' => $post_id,
        'presetPost' => $present_id,
        'vertical'  => $vertical,
        'blacklistedPartners' => $blacklist,
        'amp' => true
      ]);
      if (empty($data)) break;
      $payload[] = $data;
    }

    return $payload;
  }

  public static function get_amp_payload ($nextPost) {
    $fallback_image = get_field('featured_image_fallback', 'options');
    $fallback_image = $fallback_image && array_key_exists('sizes', (array)$fallback_image) && array_key_exists('large', $fallback_image['sizes']) ? $fallback_image['sizes']['large'] : '';

    return [
      'id' => $nextPost->ID,
      'title' => $nextPost->post_title,
      'url' => get_permalink($nextPost->ID) . '/amp',
      'image' => get_the_post_thumbnail_url($nextPost->ID) ?? $fallback_image
    ];
  }
}
