<?php

function get_twitter_handle() {
  $twitter_handle = get_field( 'twitter_handle', 'options' );
  if ( ! empty( $twitter_handle ) ) {
    return '@' . $twitter_handle;
  } else {
    return '@IamWellAndGood';
  }
}