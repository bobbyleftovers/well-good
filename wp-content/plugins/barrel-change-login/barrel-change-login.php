<?php
 /**
  * Plugin Name: Barrel Change Login
  * Description: Change login to custom URL. Find input under Settings > Permalinks and change your URL at "Change Admin Login".
  * Version: 1.0.1
  * Author: BarrelNY
  * Author URI: http://barrelny.com/
  * Text Domain: barrel-change-login
  * Domain Path: /languages
  * License: GPL-2.0
  */


defined( 'ABSPATH' ) || die( 'Do not access this file directly' );

if ( ! defined( 'CHANGE_LOGIN_Version' ) ) {
	define( 'CHANGE_LOGIN_Version', '1.0.0' );
}

if ( ! defined( 'CHANGE_LOGIN_Name' ) ) {
	define( 'CHANGE_LOGIN_Name', 'Change Login' );
}

if ( ! defined( 'CHANGE_LOGIN_PATH' ) ) {
	define( 'CHANGE_LOGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CHANGE_LOGIN_Base_Uri' ) ) {
	define( 'CHANGE_LOGIN_Base_Uri', plugin_dir_url( __FILE__ ) );
}

load_plugin_textdomain( 'barrel-change-login', false, basename( dirname( __FILE__ ) ) . '/languages' );


if ( ! @include 'class- barrel-change-login.php' ) {
	require_once CHANGE_LOGIN_PATH . 'inc/class-change-login.php';
}


function barrel_login_logo_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'barrel_login_logo_url' );
