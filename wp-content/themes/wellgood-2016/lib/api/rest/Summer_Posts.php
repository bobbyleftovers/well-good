<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;

class Summer_Posts extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/summer-posts',
      'callback' => 'get_summer_posts',
      'methods' => ['GET','POST'],
      'args' => array(
        'post_id' => array(
          'validate_callback' => 'is_numeric'
        )
      )
    )
  );

  /**
  * Get an array of posts associated to a specific page ID
  */
  static function get_summer_posts(  $request ) {
    $id = $request->get_param('post_id');
    $parent_id = get_the_parent_id($id);
    if ($parent_id === 0) {
      $parent_id = (int) $id;
    }
    $videos = get_field('videos', $id);
    $markup = '';
    $post_blocks_array = array();
    $iteration = 1;
    $children_args = array(
      'post_parent' => $parent_id,
      'post_type' => 'page'
    );

    if ($id === $parent_id) {
      $page_children = get_children($children_args);

      if (empty($page_children)) {
        array_push($post_blocks_array, get_field('articles', $parent_id));
      } else {
        foreach ($page_children as $key=>$child) {
          array_push($post_blocks_array, get_field('articles', $key));
        }
      }
    } else {
      array_push($post_blocks_array, get_field('articles', $id));
    }

    $ad_unit_iterations = count($post_blocks_array);
    foreach ($post_blocks_array as $post_blocks) {
      // Ensures $post_blocks is not empty
      if (is_array($post_blocks)) {
        foreach ($post_blocks as $post_block) {

          // Sort by date if more than 1 article in $post_block
          if ($id === $parent_id && count($post_blocks) > 1) {
            usort($post_block, "wg_post_date_comparison");
          }

          $data = [
              'id' => $id,
              'ad_units' => $ad_unit_iterations,
              'parent_id' => $parent_id
          ];

          $markup .= get_module('summer/summer-posts', $post_block, $iteration, $videos, $data);
          $iteration++;
        }
      }
    }

    return [$markup];
  }

}
