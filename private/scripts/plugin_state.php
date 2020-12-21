<?php

/**
 * Function to disable plugins outside of the LIVE environment
 * This may be relevent for things like auto-pulblishing or tracking plugins
 * 
 * @var array $plugins An array of plugins to be deactivated with this function
 * 
 */

require( '../../wp-load.php' );

if( !in_array( $_SERVER['PANTHEON_ENVIRONMENT'], array('live') ) ) :

	$plugins = array(
		'publish-to-apple-news/apple-news.php'
	);

	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/plugin.php' );
	}

	deactivate_plugins( $plugins, true );

endif;
