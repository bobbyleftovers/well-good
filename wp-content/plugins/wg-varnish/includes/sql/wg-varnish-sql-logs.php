<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WG_Varnish_SQL_Logs extends  WG_Varnish_SQL {

	/**
	 * Create Log
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */

	public function new_log( $type, $data_main, $data_secondary = array() ){

		if(!apply_filters( 'wg_varnish_db_logs', true)) return;

		$origin_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$row = array(
			'type' => $type,
			'data_main' => maybe_serialize( $data_main ),
			'data_secondary' => maybe_serialize( $data_secondary ),
			'user_id' => get_current_user_id() | null,
      'origin_url' => $origin_url,
      'origin_id' => get_the_ID() | null,
			'time' => current_time( 'mysql' )
		);
		

		$this->insert( 
			'logs', 
			$row
		);

    	return $row;
	}
	
	/**
	 * Get row schema
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */

	public function get_row_schema(){
		return array(
			'type',
			'data',
			'user_id',
            'origin_url',
            'origin_id',
			'time'
		);
	}


	/**
	 * Fetch logs
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */

	public function fetch_purged_urls( $query ){
		
		$table = $this->get_table_name('logs');	
		
		$sql_query = "SELECT * FROM $table WHERE type = 'purge_url' ORDER BY ".$query['orderby']." ".$query['order'];

		return $this->get_results($sql_query, ARRAY_A);
	
	}


	/**
	 * Clean DB every day
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
	public function daily_cleanup(){

		if(!apply_filters( 'wg_varnish_db_logs', true)) return;

		$this->delete_old_rows('logs', 24);

	}
}