<?php

namespace WG\Services;

class Redirect {

  function __construct(){
    add_action( 'save_post', array($this, 'flush_cache'), 10, 2);
  }

  function flush_cache( $post_id, $post ) {
    if ( in_array( $post->post_type, array( 'post', 'page' ) ) ) {
      delete_transient( 'wag_redirect_cache' );
    }
  }
  
}

