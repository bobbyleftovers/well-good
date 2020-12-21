<?php

/**
 * @param string $products_args - previously constructed product args
 * @param string $taxonomy - name of taxonomy to lookup
 * @param string $single_term - name of a single term to use within $taxonomy name. If this is set, we'll ONLY get posts within this term
 */
function get_category_distributed_posts($products_args, $taxonomy, $single_term = null) {
  $unordered_posts = array();
  $ordered_posts = array();
  $products = array();
  $terms = !is_null($single_term) ? array(get_term($single_term, $taxonomy)) : get_terms($taxonomy, array( 'hide_empty' => false, 'parent' => 0 ));

  $post_type = $products_args['post_type'];
  $posts_per_page = $products_args['posts_per_page'];
  $campaign_taxonomy = $products_args['tax_query'][0]['taxonomy'];
  $campaign_field = $products_args['tax_query'][0]['field'];
  $campaign_terms = $products_args['tax_query'][0]['terms'];
  $campaign_operator = $products_args['tax_query'][0]['operator'];
  $not_in = !empty($products_args['post__not_in']) ? $products_args['post__not_in'] : null;

  if (!empty($terms[0])) :
    foreach($terms as $term) :
      $slug = $term->slug;
      $id = $term->term_id;
      $args = array(
        'post_type'         => $post_type,
        'posts_per_page'    => $posts_per_page,
        'tax_query'         => array(
          array(
            'taxonomy'        => $campaign_taxonomy,
            'field'           => $campaign_field,
            'terms'           => $campaign_terms,
            'operator'        => $campaign_operator
          ),
          array(
            'taxonomy'        => $taxonomy,
            'field'           => 'term_id',
            'terms'           => $id,
            'operator'        => 'IN'
          )
        )
      );
      if ($not_in) {
        $args['post__not_in'] = $not_in;
      }
      $posts = new WP_Query($args);
      $unordered_posts[$slug] = $posts->posts;
    endforeach;
  endif;

  $greatest_count = 0;
  foreach($unordered_posts as $data_point) {
    if (count($data_point) > $greatest_count) {
      $greatest_count = count($data_point);
    }
  }

  for($i = 0; $i < $greatest_count; $i++) {
    foreach($unordered_posts as $key => $cat) {
      if (!empty($cat[$i])) {
        $cat[$i]->category = $key;
        array_push($ordered_posts, $cat[$i]);
      }
    }
  }

  return $ordered_posts;
}