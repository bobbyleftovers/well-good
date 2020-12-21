<?php

namespace WG\Services;

class Rating {

  function __construct() {

    new \WG\API\REST\Rating();
    
    add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_scripts' ) );
    
  }

  /**
   * Voting module scripts
   *
   * @return void
   */
  function enqueue_scripts() {
    // Voting Module script localization for Nonce-ing
    if( is_page_template( 'Nomination Voting Tool' ) && get_field( 'state' ) == 'voting-open' ) :
      wp_register_script( 'vote-open', '/wp-content/themes/wellgood-2016/modules/main/voting-state-voting-open/voting-state-voting-open.js' );

      wp_localize_script( 'vote-open', 'wpApiSettings', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )
      ) );

      wp_enqueue_script( 'vote-open' );
    endif;
  }


  static function get_vote_ratio( $campaign_id, $post_id = false, $force = false ) {
      $count = self::get_vote_count( $post_id );
      $total = self::get_total_votes( $campaign_id, $force );

      return round( $count / $total * 100 );
  }

  static function get_vote_count( $post_id = false ) {
      if ( ! $post_id ) {
          $post_id = get_the_ID();
      }
      global $wpdb;
      $votes = (int)$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->postmeta WHERE post_id = %d and meta_key = 'wag_vote'", $post_id ) );
      return $votes;
  }

  static function get_total_votes( $campaign_id, $flush = false ) {
      $votes = (int) get_option( '_wag_total_votes' );

      if ( $flush ) {
        global $wpdb;
        $nominees = get_field('nominees', $campaign_id);
        $nominees_id = wp_list_pluck($nominees, 'ID');
        $votes = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->postmeta AS pm INNER JOIN $wpdb->posts AS p ON p.ID = pm.post_id WHERE pm.meta_key = 'wag_vote' AND p.ID in (" . implode(',', $nominees_id) . ")");
        update_option('_wag_total_votes', $votes);
      }

      return $votes;
  }

  
}

