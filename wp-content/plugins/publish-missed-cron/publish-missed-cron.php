<?php
/**
 * Plugin Name:       Publish Missed Posts
 * Description:       W+G publish missed future posts
 * Version:           1.0.0
 * Author:            Barrel
 */
function wg_publish_missed_posts () {
  global $wpdb;

  $now = gmdate('Y-m-d H:i:00');
  $args = array(
    'public'                => true,
    'exclude_from_search'   => false,
    '_builtin'              => false
  ); 

  $post_types = get_post_types($args, 'names', 'and');
  $str = implode ('\',\'',$post_types);

  if ($str) {
    $sql = "Select ID from $wpdb->posts WHERE post_type in ('post','page','$str') AND post_status='future' AND post_date_gmt<'$now'";
  } else {
    $sql = "Select ID from $wpdb->posts WHERE post_type in ('post','page') AND post_status='future' AND post_date_gmt<'$now'";
  }

  $resulto = $wpdb->get_results($sql);

  if($resulto) {
    foreach( $resulto as $thisarr ) {
      wp_publish_post($thisarr->ID);
    }
  }
}

add_filter('cron_schedules', function ($schedules) {
  if(!isset($schedules["every15min"])){
    $schedules["every15min"] = array(
      'interval' => 15 * 60,
      'display' => __('Once every 15 minutes')
    );
  }
  return $schedules;
});

/**
 * The code that runs during plugin activation.
 */
function activate_wg_publish_missed_posts() {
	if( ! wp_get_schedule( 'scheduled_wg_publish_missed_posts' ) ) { // "scheduled_wg_publish_missed_posts" 
    wp_schedule_event( time(), 'every15min', 'scheduled_wg_publish_missed_posts' );
  }
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wg_publish_missed_posts() {
	wp_clear_scheduled_hook('scheduled_wg_publish_missed_posts');
}

/**
 * Activate / deactivate registration
 */
register_activation_hook( __FILE__, 'activate_wg_publish_missed_posts' );
register_deactivation_hook( __FILE__, 'deactivate_wg_publish_missed_posts' );

// Wire up scheduled hook
add_action( 'scheduled_wg_publish_missed_posts', 'wg_publish_missed_posts', 10, 0);