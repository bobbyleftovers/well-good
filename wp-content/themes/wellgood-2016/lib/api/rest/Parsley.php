<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use WG\Services\Parsely as ParselyService;

class Parsley extends REST_Controller {

  protected $secret = 'w5ztterVB03LGZJLfXS0hf3EvQBuFFIWew9hmVQxthU';
  protected $apikey = 'wellandgood.com';

  protected $routes = array(
    array(
      'route' => '/parsely/related',
      'callback' => 'get_related',
      'methods' => 'GET',
      'args' => array(
        'url' => array(
          'required' => true
        )
      )
    )
  );

  function __construct(){

    add_action('rest_api_init', array($this, 'register_rest_route__deprecated'));

    parent::__construct();

  }

  function register_rest_route__deprecated(){

    register_rest_route( 'api/v1', '/parsley/related', array(
      'methods' => 'GET',
      'callback' => array($this, 'get_related__deprecated'),
      'args' => array(
        'url' => array(
          'required' => true
        )
      )
    ));

  }

  function get_related( \WP_REST_Request $request ){

    $params = $request->get_params();

    return ParselyService::get_related($params);
  }

  function get_related__deprecated( \WP_REST_Request $request ){
    $data = wp_remote_get(add_query_arg($request->get_params(), 'https://api.parsely.com/v2/related'), [
      'headers' => [
        "cache-control" => "no-cache",
        "content-type" => "application/json"
      ]
    ]);

    if (is_wp_error($data)) return [];
    $json = [];
    $posts = [];
    $postPaths = [];
    $postImages = [$request->get_param('image')];
    $postTitles = [$request->get_param('title')];

    try {
      $response = json_decode( $data['body'] );
      if (!empty($response->data)) {
        foreach ($response->data as $post) {
          $url = parse_url($post->url);
          $imagename = basename($post->image_url);
          if (
            in_array($url['path'], $postPaths) ||
            in_array($imagename, $postImages) ||
            in_array(sanitize_title($post->title), $postTitles)
          ) {
            continue;
          }
          $postPaths[] = $url['path'];
          $postImages[] = $post->image_url;
          $postTitles[] = sanitize_title($post->title);
          $posts[] = $post;
        }
        $json = $posts;
      }
    } catch ( \Exception $ex ) {
      $json = [];
    }
    return $json;
  }
}
