<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load the core methods
 */
require_once dirname( __FILE__ ) . '/trait-wg-redirections-core.php';

/**
 * The core class used for redirections before WP core loads
 *
 *
 * @since      1.0.0
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */
class WG_Redirections_Redirection {


	use WG_Redirections_Core;


	/**
	 * Source URL object
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array
	 */
	private $source = array();


	/**
	 * Run redirection
	 *
	 * @since    1.0.0
	 */

	function __construct(){


		// Parse source url
		$this->fetch_source();

		// Skip endpoints that are not front end pages
		if(!$this->should_try()) return;

		// Connect DB
		if($this->connect_mysqli()->connect_errno) return;

		// Try redirection
		$this->try_redirection();

		// Close sql connection
		$this->mysqli->close();

	}

	/**
	 * Get source object URL
	 *
	 * @since    1.0.0
	 */

	function fetch_source(){

		$_SERVER['HTTP_HOST'] = !empty($_SERVER['STG_HOST']) ? $_SERVER['STG_HOST'] : $_SERVER['HTTP_HOST'];

		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->source = parse_url($url);
		
		$this->source['formatted_path'] = $this->get_formatted_path($this->source);

	}

	/**
	 * Skip REST, admin, crons, mail,... (Improve that!)
	 *
	 * @since    1.0.0
	 */

	function should_try(){

		if(!$this->source['path']) return false;

		if( defined( 'DOING_CRON' ) && DOING_CRON ) return false;

		if(substr( $this->source['path'], 0, 4 ) === '/wp-') return false;

		if(substr( $this->source['path'], 0, 9 ) === '/wg-login') return false;

		return true;
	}

	/**
	 * Query and see if redirection exists
	 *
	 * @since    1.0.0
	 */

	function try_redirection(){

		$sql = "SELECT * FROM ".$this->get_table_name()." WHERE source_uri = '". $this->source['formatted_path'] ."' AND is_active = 1";
		
		$result = $this->mysqli->query($sql);

		if(!$result || $result->num_rows === 0) return;

		$redirection = $result->fetch_assoc();

		$redirection['options'] = unserialize($redirection['options']);

		$this->redirect($redirection);

	}


	/**
	 * Make redirection
	 *
	 * @since    1.0.0
	 */

	function redirect($redirection){

		$this->update_redirection_counter($redirection['id']);
		$this->update_redirection_last_visit($redirection['id']);

		$redirect_to = $this->build_target($redirection);
		$options = array_merge($this->get_default_row()['options'], $redirection['options']);
		if($options['skip_amp_on_redirect']){
			$redirect_to = str_replace('/amp', '', $redirect_to);
		}
		if($options['remove_query_on_redirect']){
			$redirect_to = strtok($redirect_to, '?');
		}
		header("Location: ".$redirect_to, true, $redirection['http_response']);

		exit();

	}

	/**
	 * Counter row redirection
	 * @param $redirection_id
	 */
	function update_redirection_counter($redirection_id) {
		if ($redirection_id > 0) {
			$sql = "UPDATE {$this->get_table_name()} SET counter = counter + 1 WHERE id = {$redirection_id} AND is_active = 1";
			$this->mysqli->query($sql);
		}
	}

	/**
	 * Update timestamp last visit redirection
	 * @param $redirection_id
	 */
	function update_redirection_last_visit($redirection_id) {
		if ($redirection_id > 0) {
			$sql = "UPDATE {$this->get_table_name()} SET last_visit = NOW() WHERE id = {$redirection_id} AND is_active = 1";
			$this->mysqli->query($sql);
		}
	}

}
