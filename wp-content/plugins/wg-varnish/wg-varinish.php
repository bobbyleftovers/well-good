<?php

/**
 * Plugin Name:       Varnish for WordPress
 * Description:       Leaf Varnish for WordPress Plugin. Purge Varnish cache and more.
 * Version:           1.0.1
 * Author:            Leaf Group & Barrel
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
define( 'WG_VARNISH_VERSION', '1.3.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/wg-varnish-activator.php
 */
function activate_wg_varnish() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/core/wg-varnish-activator.php';
	WG_Varnish_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/wg-varnish-deactivator.php
 */
function deactivate_wg_varnish() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/core/wg-varnish-deactivator.php';
	WG_Varnish_Deactivator::deactivate();
}

/**
 * Activate / deactivate registration
 */
register_activation_hook( __FILE__, 'activate_wg_varnish' );
register_deactivation_hook( __FILE__, 'deactivate_wg_varnish' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/core/wg-varnish-init.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wg_varnish() {

	$plugin = new WG_Varnish_Init();
	$plugin->run();

}
run_wg_varnish();
