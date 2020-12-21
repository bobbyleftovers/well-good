<?php

namespace WG\Schema\Taxonomies;

class Post_Tag {

  function __construct() {
    add_action('pre_get_posts', array($this, 'tag_main_query'));
    add_action( 'init', array($this,'change_post_tag_name_to_legacy') );
    add_filter('wp_insert_post_data', array($this,'limit_terms'), '99', 2);

    // Filter terms in feeds
    add_action('pre_get_posts', array($this, 'filter_terms_in_feeds'));
  }

  function filter_terms_in_feeds(){
    if(!is_feed()) return;
    add_filter('get_the_tags', '__return_empty_array');
  }

  function tag_main_query($wp_query) {
    if ( is_admin() ) :
      return;
    endif;

    if ( $wp_query->is_tag() && $wp_query->is_main_query() ) :
        $wp_query->set( 'post_type', array( 'post', 'page' ) );
    endif;

    if ( $wp_query->is_author() && $wp_query->is_main_query() ) :
      $wp_query->set('posts_per_page', 50);
    endif;
  }

  /**
   * Limit post terms prior to inserting into or updating the database
   * @link https://developer.wordpress.org/reference/hooks/wp_insert_post_data/
   */
  function limit_terms($data, $postarr) {
    // if editing inline
    if (array_key_exists("_inline_edit", $postarr)) {
      $tags = $postarr['tax_input']['post_tag'];
      $post_type = $postarr['post_type'];

      if ($post_type == "post" && count($tags) > 3) {
        wp_die("Maximum 3 tags are allowed per post.");
      }
    }
    return $data;
  }


  function change_post_tag_name_to_legacy() {
    global $wp_taxonomies;
    $labels = &$wp_taxonomies['post_tag']->labels;
    $labels->name = 'Legacy Tags';
    $labels->singular_name = 'Legacy Tag';
    $labels->add_new = 'Add Legacy Tag';
    $labels->add_new_item = 'Add Legacy Tag';
    $labels->edit_item = 'Edit Legacy Tag';
    $labels->new_item = 'Legacy Tag';
    $labels->view_item = 'View Legacy Tag';
    $labels->search_items = 'Search Legacy Tag';
    $labels->not_found = 'No Legacy Tags found';
    $labels->not_found_in_trash = 'No Legacy Tags found in Trash';
    $labels->all_items = 'All Legacy Tags';
    $labels->menu_name = 'Legacy Tags';
    $labels->name_admin_bar = 'Legacy Tags';
    $labels->back_to_items = '← Back to Legacy Tags';
  }
}
