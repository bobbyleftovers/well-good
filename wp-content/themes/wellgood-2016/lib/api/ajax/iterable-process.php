<?php
/**
 * Script to process Iterable signups async
 */
require_once('iterable.php');

add_action('rest_api_init', function () {
  register_rest_route( 'api/v1', '/esp/signup', array(
    'methods' => 'POST',
    'callback' => 'signup_interable',
    'args' => array(
      'email' => array(
        'required' => true,
        'validate_callback' => function ($value) {
          if (is_email($value)) return true;
          return new WP_Error('invalid_param', 'Please enter a valid email address');
        }
      ),
      'form_id' => array(
        'required' => true
      )
    )
  ));
});

function signup_interable (WP_REST_Request $request) {
  $email = $request->get_param( 'email' );
  $form_id = $request->get_param( 'form_id' );
  $response = array();

  if (!function_exists('add_to_iterable')) {
    return new WP_Error('internal_error', 'Something went wrong, sorry for the inconvenience.');
  }

  $list_id = 0;
  $signup_source = '';
  $thanks = '';

  // Make sure the form is valid
  if (!empty($form_id)) {
    $form_options = get_field('signup_forms', 'options');
    $form_data = $form_options ? array_values(
      array_filter( (array) $form_options, function( $form ) use ( $form_id ) {
        return $form['signup_form_id'] === $form_id;
      } )
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
    return new WP_Error('internal_error', 'Please enter a valid email address');
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

  $data = add_to_iterable($args);

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
