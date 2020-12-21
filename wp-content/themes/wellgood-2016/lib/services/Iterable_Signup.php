<?php

namespace WG\Services;

class Iterable_Signup {

  function __construct(){

    new \WG\API\REST\Iterable_Signup();

  }

  function signup ( $email, $form_id ) {

    $response = array();

    $list_id = 0;
    $signup_source = '';
    $thanks = '';

    // Make sure the form is valid
    if (!empty($form_id)) {
      $form_options = get_field('signup_forms', 'options');
      $form_data = $form_options ? array_values(
        array_filter($form_options, function($form) use ($form_id) {
          return $form['signup_form_id'] === $form_id;
        })
      ) : array();
      $form_data = $form_data ? $form_data[0] : array();

      $success_method = $form_data
        && array_key_exists('signup_form_success_method', $form_data)
        && $form_data['signup_form_success_method']
        ? $form_data['signup_form_success_method'] : 'message';
      $signup_source = $form_data
        && array_key_exists('signup_form_id', $form_data)
        && $form_data['signup_form_id']
        ? $form_data['signup_form_id'] : '';

      $list_id = $form_data
        && array_key_exists('signup_list_id', $form_data)
        && $form_data['signup_list_id']
        ? $form_data['signup_list_id'] : 0;

      $thanks = $form_data
        && array_key_exists('signup_form_success_message', $form_data)
        && $success_method === 'message'
        && $form_data['signup_form_success_message']
        ? $form_data['signup_form_success_message'] : '';
      $redirect = $form_data
        && array_key_exists('signup_form_success_redirect', $form_data)
        && $success_method === 'redirect'
        && $form_data['signup_form_success_redirect']
        ? $form_data['signup_form_success_redirect'] : false;
    } else {
      return new \WP_Error('internal_error', 'Please enter a valid email address', array( 'status' => 400 ));
    }

    $args = array(
      'listId' => intval($list_id),
      'subscribers' => array(
        array(
          'email' => $email,
          'dataFields' => array(),
          'preferUserId' => true,
          'mergeNestedObjects' => true
        )
      )
    );

    if ($signup_source) {
      $args['subscribers'][0]['dataFields']['signupSource'] = $signup_source;
    }

    if ($interableUser = $this->get_user_withEmail($email)) {
      if (isset($interableUser->dataFields) && isset($interableUser->dataFields->emailListIds) && in_array(intval($list_id), $interableUser->dataFields->emailListIds)) {
        return new \WP_Error('internal_error', 'Already subscribed.', array( 'status' => 400 ));
      }
    }

    $data = $this->add_to_iterable($args);

    if (array_key_exists('successCount', $data) || $data['successCount'] !== 0) {
      $response['success'] = true;
      $response['response'] = $data;
      if (isset($_POST['update_user']) && $_POST['update_user'] == 'true') :
        $message = 'Your account has been updated successfully!';
      else :
        if ($thanks) :
          $message = $thanks;
        else :
          $message = 'Got it, you\'ve been added to our email list.';
        endif;
      endif;

      $response['message'] = $message;

      $response['form'] = array();
      $response['form']['successMethod'] = $success_method;
      if ($signup_source) :
        $response['form']['source'] = $signup_source;
      endif;
      if ($thanks) :
        $response['form']['thanks'] = $thanks;
      endif;
      if ($redirect) :
        $response['form']['redirect'] = $redirect;
      endif;

      return $response;
    } else {
      $response['success'] = false;
      $response['message'] = 'An error occurred, please try again later.';
      $response['response'] = $data;

      return $response;
    }
  }

  function get_user_withEmail ($email = '') {
    $data = wp_remote_get('https://api.iterable.com/api/users/getByEmail?email=' . $email, [
      'headers' => [
        "Api-Key" => get_field('signup_iterable_key', 'options'),
        "cache-control" => "no-cache",
        "content-type" => "application/json"
      ]
    ]);

    if (is_wp_error($data)) return null;
    try {
      $json = json_decode( $data['body'] );
    } catch ( \Exception $ex ) {
      $json = null;
    }
    return empty($json->user) ? null : $json->user;
  }


  function add_to_iterable($args=array(), $endpoint='https://api.iterable.com/api/lists/subscribe') {
    $response = wp_remote_post($endpoint, [
      'headers' => [
        "Api-Key" => get_field('signup_iterable_key', 'options'),
        "cache-control" => "no-cache",
        "content-type" => "application/json"
      ],
      'body'  => json_encode($args)
    ]);

    if (is_wp_error($response)) return null;

    return $response['body'] ? json_decode($response['body'], true) : false;
  }
}
