<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * The core sql query methods
 *
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/core
 * @author     Barrel
 */

trait WG_Redirections_SQL {

	 /**
	 * MySQLi object
	 *
	 * @since    1.0.0
	 * @access   public
    */

	public $mysqli;

   /**
	 * Table name
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array
    */

	public $table_prefix;
	  
	 /**
	 * Create DB connection (if global $wpdb doesn't exist)
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */
	function connect_mysqli(){

		if($this->mysqli) return $this->mysqli;
		
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		return $this->mysqli;
	}

    /**
	 * Get table name
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */
	public function get_table_name( $name = '' ) {

		global $table_prefix;

		if($this->table_prefix) return $this->table_prefix;

		if($name != ''){
			$name = '_' . $name;
		}

		$this->table_prefix = $table_prefix . str_replace('-', '_', $this->plugin_name) . $name; 


		return $this->table_prefix;
	}

	/**
	 * Create one table
	 *
	 * @since    1.0.0
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

		return dbDelta( $sql );
	}

	/**
	 * Insert row
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */

	public function insert( $name, $data ){

		global $wpdb;

		$result = $wpdb->insert( 
			$this->get_table_name( $name ), 
			$data
		);

		if(!$result){
			$this->activate_plugin(function(){
				return $this->insert($name, $data );
			});
		}

		return $result;
	}


	/**
	 * Delete old rows. Cleanup based on time
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */

	public function delete_old_rows($table, $hours = 24){

		global $wpdb;

		$table_name = $this->get_table_name( $table );

		$now = date('Y-m-d H:i:s', current_time('timestamp')-$hours*60*60);

		$sql = "DELETE FROM $table_name WHERE time < '$now'";
		
		if(!$wpdb->query( $sql )){
			return $this->activate_plugin();
		}
			
	}

	/**
	 * Get results from a sql query
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */

	public function get_results( $query, $type = ARRAY_A ){

		global $wpdb;
		
		// query output_type will be an associative array with ARRAY_A.
		$query_results = $wpdb->get_results( $query, $type );

		if(!$query_results){
			$this->activate_plugin(function(){
				$query_results = $wpdb->get_results( $query, $type );
			});
		}
				
		// return result array to prepare_items.
		return $query_results;	
	}

	/**
	 * Get results from a sql query
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */

	public function get_sql_row( $val, $col = 'id', $table = '' ){

		global $wpdb;

        $table_name = $this->get_table_name( $table );
        $row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM `$table_name`
        WHERE $col = '%s'",
        $val
        ), ARRAY_A);

		return $row;
	}

	/**
	 * Activate plugin
	 *
	 * @since    1.0.0
	 * @author   Barrel
	 */

	public function activate_plugin(){

		$this->loader->load('Core/WG_Redirections_Activator')->activate();

	}

}