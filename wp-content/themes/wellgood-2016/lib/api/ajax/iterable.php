<?php

function add_to_iterable( $args=array(), $endpoint='https://api.iterable.com/api/lists/subscribe' ) {

  $curl = curl_init();

  curl_setopt_array( $curl, array(
    CURLOPT_URL => "$endpoint",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode( $args ),
    CURLOPT_HTTPHEADER => array(
      "Api-Key: " . get_field( 'signup_iterable_key', 'options' ),
      "cache-control: no-cache",
      "content-type: application/json"
    ),
  ));

  $response = curl_exec( $curl );

  curl_close( $curl );

  return $response ? json_decode( $response, true ) : false;
}
