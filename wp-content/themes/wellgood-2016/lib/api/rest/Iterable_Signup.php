<?php

namespace WG\API\REST;

use WG\Services\Iterable_Signup as Iterable_Signup_Service;

class Iterable_Signup {

  function __construct(){

    add_action('rest_api_init', array($this, 'register_rest_route'));

  }

  function register_rest_route(){

    register_rest_route( 'api/v1', '/esp/signup', array(
      'methods' => 'POST',
      'callback' => array($this, 'signup'),
      'args' => array(
        'email' => array(
          'required' => true,
          'validate_callback' => function ($value) {
            if (is_email($value)) return true;
            return new \WP_Error('invalid_param', 'Please enter a valid email address', array( 'status' => 400 ));
          }
        ),
        'form_id' => array(
          'required' => true
        ),
        'recaptchaToken' => array(
          'required' => true,
          'validate_callback' => function ($value) {
            if (!get_field('enable_recaptcha', 'options')) return true;
            if (wg_verify_recaptcha_token($value)) return true;
            return new \WP_Error('invalid_param', 'Failed validating Recaptcha', array( 'status' => 400 ));
          }
        )
      )
    ));

  }

  function signup( \WP_REST_Request $request ){

    $email = $request->get_param( 'email' );
    $form_id = $request->get_param( 'form_id' );

    $iterable = new Iterable_Signup_Service();

    return $iterable->signup($email, $form_id);
  }
}
