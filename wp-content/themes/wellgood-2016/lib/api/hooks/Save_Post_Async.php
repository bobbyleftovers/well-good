<?php

namespace WG\API\Hooks;

use WG\API\Hooks\Async_Task;

class Save_Post_Async extends Async_Task {

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
    $this->remove_hooks();
    $post = get_post( $_POST['post_id'] );
    if ( !$post || wp_is_post_revision( $post->ID ) ) return;

    $post = apply_filters(  "save_post_async", $post );
    if($post->post_type != 'post') $post = apply_filters(  "save_".$post->post_type."_async", $post );

    do_action(  "save_post_async", $post );
    if($post->post_type != 'post') do_action(  "save_".$post->post_type."_async", $post );
  
    wp_update_post($post);

  }
}
