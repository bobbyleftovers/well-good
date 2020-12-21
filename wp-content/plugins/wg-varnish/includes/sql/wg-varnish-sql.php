<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WG_Varnish_SQL {

   /**
	 * Table name
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      array
    */

  	protected $table_prefix = 'wg_varnish';

    /**
	 * Get table name
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
	public function get_table_name( $name ) {

		global $wpdb;

		return $wpdb->prefix . $this->table_prefix . '_' . $name; 
	}

	/**
	 * Create plugin tables
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
	public function create_plugin_tables(  ) {

		if(apply_filters( 'wg_varnish_db_logs', true)){
			$this->create_table('logs',"
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			type tinytext NOT NULL,
			data_main LONGTEXT DEFAULT '' NOT NULL,
			data_secondary LONGTEXT DEFAULT '' NOT NULL,
			origin_url varchar(999) DEFAULT '',
			origin_id mediumint(9),
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			user_id mediumint(9),
			PRIMARY KEY  (id)
			");
		}
		
		//create other tables here
	}


	/**
	 * Destroy plugin tables
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
	public function destroy_plugin_tables(  ) {

	}

	/**
	 * Create one table
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */
	public function create_table( $name, $schema ){
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $this->get_table_name( $name );

		$sql = "CREATE TABLE $table_name (
			$schema
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $sql );
	}

	/**
	 * Insert row
	 *
	 * @since    1.3.0
	 * @author   Barrel
	 */

	public function insert( $name, $data ){

		global $wpdb;

		$wpdb->show_errors();

		$result = $wpdb->insert( 
			$this->get_table_name( $name ), 
			$data
		);

		return $result;
	}


	public function delete_old_rows($table, $hours = 24){

		global $wpdb;

		$table_name = $this->get_table_name( $table );

		$now = date('Y-m-d H:i:s', current_time('timestamp')-$hours*60*60);

		$sql = "DELETE FROM $table_name WHERE time < '$now'";
		
		$wpdb->query( $sql );
			
	}

	public function get_results( $query, $type = ARRAY_A ){

		global $wpdb;
		
		// query output_type will be an associative array with ARRAY_A.
		$query_results = $wpdb->get_results( $query, $type );
				
		// return result array to prepare_items.
		return $query_results;	
	}

}