<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;
use \WG\Services\Rating;

class Nominees extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/campaign/(?P<post_id>[\d]+)/nominee/(?P<id>[\d]+)/vote',
      'callback' => 'add_nominee_vote',
      'args' => array(
          'id' => array(
              'validate_callback' => 'is_numeric'
          ),
          'post_id' => array(
              'validate_callback' => 'is_numeric'
          ),
      )
    ),
    array(
      'route' => '/nomination-forms',
      'callback' => 'add_nominee'
    )
  );


  /*
  * API Endpoint to add new nominee
  */

  static function add_nominee( $request ) {

    // 1. Get Typeform data from payload //
    $response = ( $request->get_body() ? $request->get_body() : "Hmm, doesn't look like this request has a body" );
    $response = json_decode($response, true);
    $fields = $response['form_response']['definition']['fields'];
    $form_data = $response['form_response']['answers'];

    // 2. Set Reebok API Credentials //
    $public_key = get_field('reebok_public_key', 'options');
    $secret_key = get_field('reebok_secret_key', 'options');
    $source_ID = get_field('reebok_source_id', 'options');
    $current_date = date('Y/m/d h:i:s', time());

    // 3. Form Definitions //
    // -- Typeform will send two different sets of data - 'Self' or 'Trainer' - depending on how the user fills out the form,
    // -- $nomination_def is used to dynamically determine which field set we should use to retrieve answers
    $nomination_def = 'Ln5Qj0wtrpjM';
    $self_or_trainer = $form_data[ self::find_definition_index( $nomination_def, $fields ) ]['choice']['label'];

    $field_definitions = array(
        'Self' => array(
            'fname_def' => 'KKmun6vHpTvE',
            'lname_def' => 'cHNpbsgIoNJs',
            'email_def' => 'xDAvM2MBB1eS',
            'dob_def' => 'EQlb8YvJqF3c',
            'location_def' => 'jwKPsNIZ0Kqy',
            'gender_def' => 'J6J5GdwUaZUZ',
            'specialties_def' => '61487558',
            'gym_def' => 'hqKOK3yU6GuC',
            'agree_def' => 'L31RM2MTMBua'
        ),
        'Trainer' => array(
            'fname_def' => 'dwxBHVM3wpdu',
            'lname_def' => 'D57XnUEdh8p2',
            'email_def' => 'FvgIqTcLnvWn',
            'dob_def' => 'edhjo8OGTdnR',
            'location_def' => 'PBpjVwu2qEk4',
            'gender_def' => 'sSmgxOMzF38X',
            'specialties_def' => '61484717',
            'gym_def' => 'yOCXB8Do4VBZ',
            'agree_def' => 'Mnx09eM0yVRj'
        )
    );

    // 4. Establish the field definition set based on which submission option the user selected //
    $definition_set = $field_definitions[ $self_or_trainer ];

    // 5. Set the appropriate definition key for each field //
    $fname_def       = $definition_set['fname_def'];
    $lname_def       = $definition_set['lname_def'];
    $email_def       = $definition_set['email_def'];
    $dob_def         = $definition_set['dob_def'];
    $location_def    = $definition_set['location_def'];
    $gender_def      = $definition_set['gender_def'];
    $specialties_def = $definition_set['specialties_def'];
    $gym_def         = $definition_set['gym_def'];
    $agree_def       = $definition_set['agree_def'];

    // 6. Get and Encode the Data from Typeform Payload //
    $data = array(
        'Email' => $form_data[ self::find_definition_index( $email_def, $fields) ]['email'],
        'Locale' => 'en-US',
        'FirstName' => $form_data[ self::find_definition_index( $fname_def, $fields ) ]['text'],
        'LastName' => $form_data[ self::find_definition_index( $lname_def, $fields ) ]['text'],
        'BirthDate' => gmdate( 'Y-m-d\TH:i:s\Z', strtotime( $form_data[ self::find_definition_index($dob_def, $fields) ]['date'] ) ),
        'Source' => $source_ID,
        'Gender' => self::get_gender( $form_data[ self::find_definition_index( $gender_def, $fields) ]['choice']['label'] ),
        'Gym' => $form_data[ self::find_definition_index( $gym_def, $fields) ]['text'],
        'MemberType' => 0,
        'Specialities' => array_map('self::format_specialties', $form_data[ self::find_definition_index( $specialties_def, $fields) ]['choices']['labels'] ),
        'AmfAgree' => ( strcasecmp( $form_data[ self::find_definition_index( $agree_def, $fields) ]['choice']['label'], 'Yes' ) == 0 ? 1 : 0 ),
        'AmfConsent' => array(
            'Version' => null,
            'ConsentType' => 0,
            'Agree' => true
        ),
        'sendMail' => 'Y'
    );
    $data = json_encode($data);

    if( strcasecmp( $form_data[ self::find_definition_index( $agree_def, $fields) ]['choice']['label'], 'Yes' ) == 0 ){

        // 7. Post Data to Reebok API //
        $url = 'https://hp.cms7.reebok.com/api/R1Subscription/Create';
        $checksum = 'POST,' .  $current_date . ',/api/R1Subscription/Create';
        $send_data = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 300, // this is set in seconds
            'headers' => array(
                'accessKeyId' => $public_key,
                'cache-control' => 'no-cache',
                'checksum' => hash_hmac( 'sha256', $checksum, $secret_key),
                'Content-Type' => 'application/json',
                'time' => $current_date
            ),
            'body' => $data
            )
        );
        if ( is_wp_error( $send_data ) ) {
            $result_data = $send_data->get_error_message();
        } else {
            $result_data = json_encode($send_data);
        }
    }else {
        $result_data = 'User did not consent to their information being used so we will not send it to Reebok';
    }

    // 8. Log the Reebok API Response to a temporary HTTP request bin to easily monitor it //
    // -- you can check the response object by visiting $log_url . '?inspect'
    // -- if the $log_url is an expired url, just create a new one and update the $log_url
    $log_toggle = get_field('response_monitoring_toggle', 'options');
    $log_url = get_field('response_monitoring_url','options');

    if( $log_url && $log_toggle == 'enabled' ){
        $log_data = wp_remote_post( $log_url, array(
            'method' => 'POST',
            'timeout' => 300,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'time' => gmdate('Y-m-d\TH:i:s\Z')
            ),
            'body' => $result_data
            )
        );
        if ( is_wp_error( $log_data ) ) {
            $log_result = $log_data->get_error_message();
            echo $log_result;
        } else {
            print_r($log_data);
        }
    }
    return;
}

  /*
  * API Endpoint Callback for voting campaign
  */
  static function add_nominee_vote( $params ) {
    global $wpdb;

    if( $params->get_header('X-WP-Nonce') === null ){
        return new \WP_Error( 'invalid_cookie_nonce', 'No Nonce was provided with this request', array( 'status' => 510 ) );
    }

    $campaign = get_post( (int) $params['post_id'] );
    if (empty( $campaign ) || $campaign->post_type !== 'page' ) {
        return new \WP_Error( 'invalid_campaign', 'Invalid Campaign ID', array( 'status' => 404 ) );
    }

    $cookie_key = "STYXKEY_wag_voted_{$campaign->ID}";

    if ( ! empty( $_COOKIE[$cookie_key] ) ) {
        return new \WP_Error( 'already_voted', 'You have already casted a vote.', array( 'status' => 403 ) );
    }

    $nominee = get_post( (int) $params['id'] );
    if (empty( $nominee ) || $nominee->post_type !== 'nominees' ) {
        return new \WP_Error( 'invalid_nominee', 'Invalid Nominee ID', array( 'status' => 404 ) );
    }

    add_post_meta( $nominee->ID, 'wag_vote', 1, false );
    $https = isset($_SERVER['HTTP_USER_AGENT_HTTPS']) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON';
    setcookie( $cookie_key, $nominee->ID, time() + 30 * DAY_IN_SECONDS, '/', $_SERVER['HTTP_HOST'], $https, true );

    $current_votes = get_vote_count( $nominee->ID );
    $ratio = Rating::get_vote_ratio( $campaign->ID, $nominee->ID, true );

    return array(
        'votes' => $current_votes,
        'ratio' => $ratio
    );
  }

  /**
	 * @internal Typeform payload delivers the set of questions and answers seperately. The index position of a question set will match the index position of it's answer
	 *
	 * @param string $key A unique ID from typeform used to identify a specific question
	 * @param array $array The array of questions delivered by typeform -- used as a haystack for answer mapping
	 *
	 */
	function find_definition_index($key, $array){

		foreach($array as $index => $def){

			if( $key == $def['id'] ){
				return $index;
			}

		}
		return false;
	}

	/**
	 * @param string $gender Value of 'Gender' answer delivered on typeform payload
	 */

	function get_gender( $gender ){
		if ( strcasecmp($gender,'male') == 0 ){
			return 1;
		}else if( strcasecmp($gender,'female') == 0 ){
			return 2;
		}else {
			return 0;
		}
	}

	/**
	* @param string $s A single specialty from the set of specialties delievered on the typeform payload
	*/
	function format_specialties($s){

		return strtolower( str_replace(' ', '-', $s) );
	}



}
