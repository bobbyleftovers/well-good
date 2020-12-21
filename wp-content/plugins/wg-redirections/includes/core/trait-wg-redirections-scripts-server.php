<?php

/**
 *
 *
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

trait WG_Redirections_Scripts_Server {

	public $is_develop_server = false;

	public $dev_server = array(
		'protocol' => 'https',
		'host' => 'localhost',
		'port' => 8085
	); 

	/**
	 * Get webpack dev server
	 *
	 * @since    1.0.0
	 */
	public function get_dev_server($part = false){

		if($part) return $this->dev_server[$part];

		return $this->dev_server['protocol'].'://'.$this->dev_server['host'].':'.$this->dev_server['port'].'/';

	}

	/**
	 * Register the scripts and stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function get_scripts_uri(){
		//URI
		if ( $this->is_develop_serve() ) {
			$scripts_uri =  $this->get_dev_server();
		 } else {
			$scripts_uri =   plugin_dir_url( dirname(__DIR__) ) . 'dist/';
		}
		return $scripts_uri;
	}

	/**
	 * Check webpack server is running.
	 *
	 * @since    1.0.1
	 */
	private function is_develop_serve(){

		if(function_exists('is_local') && !is_local()) return false;

		if($this->is_develop_server != null) return $this->is_develop_server;
	
		$connection = @fsockopen('https://'.$this->get_dev_server('host'), $this->get_dev_server('port'));

		if ( $connection ) {
			$this->is_develop_server = true;
		} else {
			$this->is_develop_server = false;
		}

		return $this->is_develop_server;
	}

}
