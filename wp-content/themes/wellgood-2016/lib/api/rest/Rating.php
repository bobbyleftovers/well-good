<?php

namespace WG\API\REST;

use WG\API\REST\REST_Controller;

class Rating extends REST_Controller {

  protected $routes = array(
    array(
      'route' => '/(?P<post_id>[\d]+)/rating',
      'callback' => 'add_rating',
      'args' => array(
        'post_id' => array(
          'validate_callback' => 'is_numeric'
        )
      )
    )
  );

  /*
  * API Endpoint Callback for rating module
  */
  static function add_rating( $params ) {

    $vote = (int) $params['vote'];
    $post_id = (int) $params['id'];

    self::set_rating_cookie_and_save_vote( $vote, $post_id, isset($_COOKIE['STYXKEY_wag_rating']) );


    // For testing:

    $existing_votes = self::get_decoded_rating_meta($post_id);
    if(isset($_COOKIE['STYXKEY_wag_rating'])) {
      $cookie_data = json_decode( stripslashes($_COOKIE['STYXKEY_wag_rating']), true );
    }
    $old_vote = isset($cookie_data) ? $cookie_data[$post_id] : 'No votes yet';

    return [
            'Cookie vote: ' . $old_vote,
            $existing_votes
          ];
  }

  /*
  * Set a cookie to store the vote with a key of the post id
  *
  * @param int $vote Vote received from the Ajax request
  * @param int $post_id Post ID received from the Ajax request
  * @param bool $cookie_exists e.g. isset($_COOKIE['foo'])
  */
  static function set_rating_cookie_and_save_vote($vote, $post_id, $cookie_exists) {

    $COOKIE_TIMEOUT = time() + (86400 * 30);
    $cookie_array = $cookie_exists ? json_decode(stripslashes($_COOKIE['STYXKEY_wag_rating']), true) : [];
    $cookie_array[$post_id] = $vote;
    $cookie_data = json_encode($cookie_array);

    self::setcookie('STYXKEY_wag_rating', $cookie_data, $COOKIE_TIMEOUT, "/");
    self::save_rating_to_meta( $vote, $post_id );
  }

  /*
  * Retrieve array of rating votes from post_meta and
  * either add new post meta, or update the existing
  * post meta with the new vote
  *
  * @param int $vote Vote received from the Ajax request
  * @param int $post_id Post ID received from the Ajax request
  */
  static function save_rating_to_meta($vote, $post_id) {

    $existing_votes = self::get_decoded_rating_meta( $post_id );

    if( $existing_votes ) :
      $new_votes = self::get_updated_rating_arr( $existing_votes, $vote, $post_id );
      $new_votes_json = json_encode( $new_votes );
      update_post_meta( $post_id, 'wag_rating', $new_votes_json );
    else :
      $initial_votes = [$vote];
      $initial_votes_json = json_encode( $initial_votes );
      add_post_meta( $post_id, 'wag_rating', $initial_votes_json, true );
    endif;

  }

  /*
  * Get array of rating votes from post_meta
  * @param int $post_id Post ID received from the Ajax request
  * @return array || false Return decoded JSON array, or false if no post_meta
  */
  static function get_decoded_rating_meta($post_id) {
    $existing_votes = get_post_meta( $post_id, 'STYXKEY_wag_rating', true );
    if ( !$existing_votes ) return false; // Exit if there are no votes
    $existing_votes_arr = json_decode( $existing_votes, true );
    return $existing_votes_arr;
  }

  /*
  * Add new votes to the array already extracted from meta.
  * If there is a vote in the cookie, find the value's position in the array and replace it,
  * otherwise, add a new value to the array
  *
  * @param array $decoded_arr Decoded JSON from post_meta
  * @param int $vote Vote received from the Ajax request
  * @param int $post_id Post ID received from the Ajax request
  * @return array Array modified to account for cookie
  */
  static function get_updated_rating_arr( $decoded_arr, $vote, $post_id ) {

    if( isset($_COOKIE['STYXKEY_wag_rating'])) {
      $cookie_data = json_decode( stripslashes($_COOKIE['STYXKEY_wag_rating']), true );
      $old_vote = $cookie_data[$post_id];
    }

    if( isset($old_vote) ) {
      $old_vote_index = array_search( $old_vote, $decoded_arr );
      $decoded_arr[$old_vote_index] = $vote;
    } else {
      array_push( $decoded_arr, $vote );
    }

    return $decoded_arr;
  }

  
}

