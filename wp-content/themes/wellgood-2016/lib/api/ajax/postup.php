<?php

/*******************************
 Define statements
********************************/
$postup_username = get_field('postup_username', 'options');
$postup_password = get_field('postup_password', 'options');

$base_auth = base64_encode($postup_username . ':' . $postup_password);
define( 'POSTUP_TOKEN', $base_auth);

/*******************************
 Function to asynchronously submit new signup to PostUp
 Example usage:

 $args = array(
     'importTemplateId' => {{ int }},
     'data' => array(
       "example@domain.com"
     )
 );

********************************/
function add_to_postup( $args = array(), $endpoint = 'https://api.postup.com/api/import' )
{

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "$endpoint",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($args),
    CURLOPT_HTTPHEADER => array(
      "authorization: Basic " . POSTUP_TOKEN,
      "cache-control: no-cache",
      "content-type: application/json"
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  return $response ? json_decode( $response, true ) : false;
}
