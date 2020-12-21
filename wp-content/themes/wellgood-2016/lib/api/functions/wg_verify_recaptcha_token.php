<?php
function wg_verify_recaptcha_token ($token) {
  $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
    'headers' => [
      "cache-control" => "no-cache",
      "content-type" => "application/x-www-form-urlencoded"
    ],
    'body' => [
      'secret' => get_field('recaptcha_site_secret', 'options'),
      'response' => $token,
      'remoteip' => $_SERVER['REMOTE_ADDR']
    ]
  ]);

  if (is_wp_error($response)) return false;
  $body = json_decode($response['body'], true);
  return $body['success'];
}
