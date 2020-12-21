<?php


if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('WG_Varnish_Async_Save_Post') ) :

class WG_Varnish_Async_Save_Post extends WG_Varnish_Async {

  protected $action = 'save_post';

	/**
	* Prepare data for the asynchronous request
	*/

	protected function prepare_data( $data ) {
	  return array( 'post_id' => $data[0] );
  }
  
  /**
	* Run the async task action
  */
  
	protected function run_action() {

    $post = get_post( $_POST['post_id'] );
    if ( !$post || wp_is_post_revision( $post->ID ) ) return;

    $post = apply_filters(  "wg_save_post_async", $post );
    if($post->post_type != 'post') $post = apply_filters(  "wg_varnish_save_".$post->post_type."_async", $post );

    do_action(  "wg_save_post_async", $post );
    if($post->post_type != 'post') do_action(  "wg_varnish_save_".$post->post_type."_async", $post );

    wp_update_post($post);

  }
}

endif;