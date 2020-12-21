<?php

/**
 * Plugin Name:       Redirections for WordPress
 * Description:       W+G Redirections for WordPress Plugin
 * Version:           1.0.0
 * Author:            Barrel
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WG_REDIRECT_VERSION', '1.2.0' );

/**
 * Load the core plugin class 
 */
require plugin_dir_path( __FILE__ ) . 'includes/core/class-wg-redirections.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/wg-redirections-activator.php
 */
function activate_wg_redirections() {
	WG_Redirections::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/wg-redirections-deactivator.php
 */
function deactivate_wg_redirections() {
	WG_Redirections::deactivate();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
WG_Redirections::execute();


/**
 * Activate / deactivate registration
 */
register_activation_hook( __FILE__, 'activate_wg_redirections' );
register_deactivation_hook( __FILE__, 'deactivate_wg_redirections' );
