<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use WG\Services\DoubleClick as DoubleClick_Service;

class DoubleClick extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/get-inline-ad',
      'callback' => 'get_ad_markup',
      'methods' => 'GET'
    )
  );

  /*
  * Request response logic for ad_tag update
  * @return [(int) $index, (arr) $status_code)];
  */
  function update_ad_cats( $request ) {
    $data = $request['data'];
    $index = $request['index'];
    $target = $data[0];
    $url = $data[0];
    $adcats = array_filter(array(
      $data[1],
      $data[2],
      $data[3],
      $data[4]
    ), function($value) { return $value !== ''; });
    $errors = array(
      'no_post'
    );
    if (is_numeric($target)) :
      $post = get_post((int) $target);
    else :
      $path = trim(parse_url($target, PHP_URL_PATH), '/');
      $parts = explode('/', $path);
      $slug = count($parts) > 1 ? end($parts) : $parts[0];
    
      $post = get_posts(array(
        'name' => $slug,
        'posts_per_page' => 1,
        'post_status' => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
        'post_type' => 'post'
      ))[0];
    endif;
    if ($post) :
      $post_adcats = array();
      
      unset($errors[0]);

      foreach($adcats as $i => $adcat_name) :
        if (!empty($adcat_name)) :
          preg_match('/^[a-zA-Z0-9_]*$/i', $adcat_name, $matches);
          $is_valid = $matches ? true : false;
        else :
          $is_valid = false;
        endif;

        $adcat = $is_valid ? get_term_by('name', $adcat_name, 'ad_cat') : null;
        if ($adcat) :
          $id = $adcat->term_id;
        else :
          if ($i === 0) :
            $id = wp_insert_term($adcat_name, 'ad_cat');
          else :
            $parent = $i == 0 ? NULL : get_term_by('name', $adcats[$i - 1], 'ad_cat');
            $args = $parent ? array('parent' => $parent->term_id) : array();
            $id = wp_insert_term($adcat_name, 'ad_cat', $args);
          endif;
        endif;
        array_push($post_adcats, $id);
      endforeach;

      $current_ad_cats = wp_get_post_terms($post->ID, 'ad_cat');
      wp_remove_object_terms($post->ID, $current_ad_cats, 'ad_cat');

      wp_set_object_terms($post->ID, $post_adcats, 'ad_cat', true);
      wp_set_object_terms($post->ID, 'updated-ad-cats', 'dev_tag', true);
    endif;
    
    update_field('ad_cat_current_csv_position', $index, 'options');
    if (in_array('no_post', $errors)) :
      $status_code = 'error';
    elseif (count($errors) > 0) :
      $status_code = 'issue';
    else :
      $status_code = 'success';
    endif;
    
    return array($index, $status_code, $data);
  }

  /*
  * Load ad modules
  * @return string $markup - ad markup;
  */
  function get_ad_markup($request) {
    $page = $request['page'];
    $iteration = $request['iteration'];
    $slot = $request['slot'];

    $unit = DoubleClick_Service::get_ad_unit($slot, $page, $iteration);
    $ad_id = DoubleClick_Service::get_ad_id($unit);

    $ad_markup = '';
    if ($slot && $ad_id) :
      $ad_markup .= '<section class="advertisement ad-position-' . $slot . '"><div id="' . $ad_id . '" class="advertising-adslot"></div></section>';
    endif;

    return $ad_markup;
  }
}
