<?php

/**
 * Function to disable plugins outside of the LIVE environment
 * This may be relevent for things like auto-pulblishing or tracking plugins
 *
 * @var array $plugins An array of plugins to be deactivated with this function
 *
 */


require( '../../wp-load.php' );

// if ( ! wp_next_scheduled( 'my_task_hook' ) ) {
//   wp_schedule_event( time(), 'hourly', 'my_task_hook' );
// }
