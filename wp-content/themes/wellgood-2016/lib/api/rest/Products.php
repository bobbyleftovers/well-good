<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;

class Products extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/product-guide-quiz',
      'callback' => 'get_product_quiz_results'
    ),
    array(
      'route' => '/product-guide-products',
      'callback' => 'get_product_guide_products'
    )
  );

  /*
  * Request response logic for product guide quiz
  * @return array [$post_markup (html)];
  */
  static function get_product_quiz_results(  $request ) {
    $markup = '';
    $json_text = $request['constraints'];
    $decoded_text = html_entity_decode($json_text);
    $constraints = json_decode($decoded_text, JSON_OBJECT_AS_ARRAY)[0];
    $id = $request['post_id'];
    $parent_id = get_the_parent_id($id);
    $campaign_id = get_field('product_guide_campaign', $parent_id);

    $quiz_args = array(
      'posts_per_page'  => 3,
      'post_type'       => 'products',
      'orderby'         => 'rand',
      'tax_query'       => array(
        array(
          'taxonomy'      => 'product_campaigns',
          'field'         => 'term_id',
          'terms'         => $campaign_id,
          'operator'      => 'IN'
        )
      )
    );

    if (array_key_exists('categories', $constraints)) :
      $constraint_categories = array();
      $all_categories = $constraints['categories'];

      foreach($all_categories as $category_id) :
        array_push($constraint_categories, $category_id);

      endforeach;

      $category_args = array(
        'taxonomy'      => 'product_categories',
        'field'         => 'term_id',
        'terms'         => $constraint_categories,
        'operator'      => 'IN'
      );
      array_push( $quiz_args['tax_query'], $category_args );

    endif;

    if (array_key_exists('minPrice', $constraints) || array_key_exists('maxPrice', $constraints))  :
      $constraint_min_price = isset($constraints['minPrice']) ? (int) $constraints['minPrice'] : null;
      $constraint_max_price = isset($constraints['maxPrice']) ? (int) $constraints['maxPrice'] : null;
      $meta_query = array();
      $price_args = array(
        'key'   => 'product_price',
        'type'  => 'NUMERIC'
      );

      if (isset($constraint_min_price) && isset($constraint_max_price)) :
        $price_args['compare'] = 'BETWEEN';
        $price_args['value'] = array($constraint_min_price, $constraint_max_price);

      elseif (isset($constraint_min_price)) :
        $price_args['compare'] = '>';
        $price_args['value'] = $constraint_min_price;

      elseif (isset($constraint_max_price)) :
        $price_args['compare'] = '<';
        $price_args['value'] = $constraint_max_price;

      endif;

      array_push($meta_query, $price_args);
      $quiz_args['meta_query'] = $meta_query;
    endif;

    $recent_posts = get_posts($quiz_args);

    if (!empty($recent_posts)) :
      foreach ($recent_posts as $i => $post) :
        $markup .= get_module('product-guide/product-guide-recommendation', $post);
      endforeach;
      return [$markup];

    endif;
    return;
  }

  /*
  * Request response logic for product guide product loader
  * @return array [$header (html), $content (html)];
  */
  static function get_product_guide_products(  $request ) {
    $id = $request['post_id'];
    $header = '';
    $hero = '';
    $content = '';
    $header .= get_module('product-guide/product-guide-header', $id);
    $hero .= get_module('product-guide/product-guide-hero', $id);
    $content .= get_module('product-guide/product-guide-grid', $id);

    return [$header, $hero, $content];
  }

}