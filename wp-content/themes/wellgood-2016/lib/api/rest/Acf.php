<?php

namespace WG\API\REST;

class Acf {

  static $transfer_to_dev_tags = array(
    'parsing-error-no-dom',
    'updated-ad-cats',
    'updated-ad-cats-error',
    'related-content-success',
    'related-content-error'
  );

  function __construct() {
    add_filter('rest_pre_echo_response', array($this, 'update_acf_fields'), 10, 3);
    add_filter( 'acf/fields/post_object/query', array($this, 'post_object_query_filter'), 10, 3 );
    add_filter( 'acf/fields/relationship/query', array($this, 'relationship_query_filter'), 10, 3 );
    add_filter( 'posts_where', array($this, 'title_filter'), 10, 2 );
  }

  function transfer_backend_to_dev_tag($post_id, $tag) {
    $backend_term = get_term_by('slug', $tag, 'backend_tag');
    $dev_term = get_term_by('slug', $tag, 'dev_tag');

    if ($dev_term) :
      $dev_id = $dev_term->term_id;
    else :
      $dev_id = wp_insert_term(
        $tag,
        'dev_tag',
        array('slug' => $tag)
      );
    endif;

    wp_remove_object_terms($post_id, $backend_term->term_id, 'backend_tag');
    wp_set_object_terms($post_id, $dev_id, 'dev_tag', true);
  }

  function update_acf_fields($response, $object, $request) {
    $params = $request->get_params();
    if ($request->get_method() != 'POST' || !array_key_exists('id', $params)) return $response;

    $post_id = strval($params['id']);

    $hero_tag = array_key_exists('hero_tag', $params) ? intval($params['hero_tag']) : '';
    $tag_1 = array_key_exists('tag_1', $params) ? intval($params['tag_1']) : '';
    $tag_2 = array_key_exists('tag_2', $params) ? intval($params['tag_2']) : '';

    $transfer_tags = array();

    $backend_tags = array_map(function($tag) {
      if (in_array($tag, self::$transfer_to_dev_tags)) :
        array_push($transfer_tags, $tag);
      endif;

      $id = null;
      $obj = get_term_by('slug', $tag, 'post_tag');
      $exists = term_exists($obj->name, 'backend_tag');
      if ($obj && !$exists) :
        $id = wp_insert_term(
          $tag,
          'backend_tag',
          array('slug' => $obj->slug)
        );
      elseif ($obj && $exists) :
        $id = $exists['term_id'];
      endif;

      if ($id) :
        return intval($id);
      endif;

      return;
    }, explode('|', $params['backend_tag']));

    $dev_tags = array_map(function($legacy_category) {
      $dev_tag = "legacy-category-$legacy_category";
      $id = null;
      $obj = get_term_by('slug', $dev_tag, 'dev_tag');
      if ($obj) :
        $id = $obj->term_id;
      else :
        $id = wp_insert_term(
          $dev_tag,
          'dev_tag',
          array('slug' => $dev_tag)
        );
      endif;

      if ($id) :
        return intval($id);
      endif;

      return;
    }, explode('|', $params['legacy_category']));

    $response['acf']['hero_tag'] = $hero_tag;
    $response['acf']['tag_1'] = $tag_1;
    $response['acf']['tag_2'] = $tag_2;

    foreach(wp_get_object_terms($post_id, 'backend_tag') as $backend_tag_check) :
      if (in_array($backend_tag_check->slug, self::$transfer_to_dev_tags)) :
        self::transfer_backend_to_dev_tag($post_id, $backend_tag_check->slug);
      endif;
    endforeach;

    update_post_meta($post_id, 'hero_tag', $hero_tag);
    update_post_meta($post_id, 'tag_1', $tag_1);
    update_post_meta($post_id, 'tag_2', $tag_2);
    wp_set_object_terms($post_id, $backend_tags, 'backend_tag', true);
    wp_set_object_terms($post_id, $dev_tags, 'dev_tag', true);

    return $response;
  }

  function post_object_query_filter ($args, $field, $post_id) {
    if ($field['type'] === 'post_object') {
      $args['post_status'] = array( 'publish', 'future' );
      $args['title_filter'] = $args['s'];
    }

    return $args;
  }

  function relationship_query_filter ($args, $field, $post_id) {
    $args['post_status'] = array( 'publish', 'future' );
    $args['title_filter'] = $args['s'];
    return $args;
  }

  function title_filter ($where, $wp_query) {
    if (
      !empty($wp_query->get( 'title_filter' ))
    ) {
      global $wpdb;
      $search_term = $wp_query->get( 'title_filter' );
      $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
    }
    return $where;
  }
}
