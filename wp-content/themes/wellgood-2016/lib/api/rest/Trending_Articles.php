<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use WG\Services\Parsely;


class Trending_Articles extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/trending_articles/parsely',
      'callback' => 'get_top_posts_parsely',
      'methods' => 'GET'
    ),
    array(
      'route' => '/trending_articles/search',
      'callback' => 'get_parsely_search',
      'methods' => 'GET'
    ),
    array(
      'route' => '/trending_articles/wp',
      'callback' => 'get_top_posts_wp',
      'methods' => 'GET'
    )
  );

  function get_top_posts_parsely( $request ) {

    return Parsely::get_top_posts($request->get_params());

  }

  function get_parsely_search( $request ) {

    return Parsely::search($request->get_params());

  }

  function get_top_posts_wp( $request ) {

    $category = $this->get_param($request, 'category');

    $query = array(
      'post_type'  =>  'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => $this->get_max($request)
    );

    if($category){
      $query['category_name'] = $category;
    }

    $query = new \WP_Query($query);

    foreach($query->posts as &$post){
      $override = get_field( 'override_automatic_title_casing', $post->ID );

      $post->title = verify_title_case( $post->post_title, $post->post_date, $override );
      $post->excerpt = wp_trim_words(strip_shortcodes(strip_tags($post->post_content)), 20);
      unset($post->post_content);
      $author = get_user_by( 'ID', $post->post_author );
      $post->author = $author->display_name;
      $post->url = get_the_permalink($post->ID);
      $post->image_url = get_the_post_thumbnail_url($post,'medium');
      $post->date = date('F d, Y',strtotime($post->post_date));
      // $post->image_thumbnail = get_the_post_thumbnail_url($post,'thumbnail');
    }
    return $query->posts;
  }

  function get_max($request){
    return $this->get_param($request, 'max', 3);
  }

  function get_param($request, $param, $default = false){
    $params = $request->get_params();
    $val = (int) (is_array($params) && isset($params[$param])) ? $params[$param] : $default;
    return $val;
  }
}
