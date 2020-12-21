<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WG_Varnish
 * @subpackage WG_Varnish/includes/admin
 * @author     Barrel
 */
class WG_Varnish_Admin_Logs_Table extends WG_Varnish_Admin_Table {

	public $loader;

	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @since 1.3.0
	 * 
	 * @return array
	 */	
	public function get_columns() {		
		return array(	 
			'data_main'	=> 'Purged URL',
			'data_secondary'	=> 'Varnish servers',
			'origin_url' => 'Origin',
			'user_id'	=> 'User',			
			'time'	=> 'Time',
		);
	}

	/**
	 * Get a list of sortable columns. The format is:
	 * 'internal-name' => 'orderby'
	 * or
	 * 'internal-name' => array( 'orderby', true )
	 *
	 * The second format will make the initial sorting order be descending
	 *
	 * @since 1.3.0
	 * 
	 * @return array
	 */
	protected function get_sortable_columns() {
		
		/*
		 * actual sorting still needs to be done by prepare_items.
		 * specify which columns should have the sort icon.
		 * 
		 * key => value
		 * column name_in_list_table => columnname in the db
		 */
		$sortable_columns = array (	
				'data_main' => 'data_main',
				'time'=>'time',
				'origin_url'=>'origin_id',
				'user_id'=>'user_id'
			);
		
		return $sortable_columns;
	}	

	/**
	 *  Empty table
	 */

	public function no_items() {
		echo 'No logs avaliable.';
	}

		
	/**
	 * Prepares the list of items for displaying.
	 * 
	 * Query, filter data, handle sorting, and pagination, and any other data-manipulation required prior to rendering
	 * 
	 * @since   1.0.0
	 */
	public function prepare_items() {
		
		$this->_column_headers = $this->get_column_info();
		
		// fetch table data
		$table_data = $this->fetch_table_data();
		
		// required for pagination
		$rows_per_page = $this->get_items_per_page( 'rows_per_page' );
		$table_page = $this->get_pagenum();		
		
		// provide the ordered data to the List Table.
		// we need to manually slice the data based on the current pagination.
		$this->items = array_slice( $table_data, ( ( $table_page - 1 ) * $rows_per_page ), $rows_per_page );

		// set the pagination arguments		
		$total_users = count( $table_data );
		$this->set_pagination_args( array (
					'total_items' => $total_users,
					'per_page'    => $rows_per_page,
					'total_pages' => ceil( $total_users/$rows_per_page )
				) );
	}

	/*
	 * Fetch table data from the WordPress database.
	 * 
	 * @since 1.3.0
	 * 
	 * @return	Array
	 */
	
	public function fetch_table_data() {

		$orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'time';
		$order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';

		return $this->loader->load('SQL/WG_Varnish_SQL_Logs')->fetch_purged_urls(
			array(
				'orderby' => $orderby,
				'order' => $order
			)
		);
	}
	
	/*
	 * Filter the table data based on the user search key
	 * 
	 * @since 1.3.0
	 * 
	 * @param array $table_data
	 * @param string $search_key
	 * @returns array
	 */
	public function filter_table_data( $table_data, $search_key ) {
		$filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
			foreach( $row as $row_val ) {
				if( stripos( $row_val, $search_key ) !== false ) {
					return true;
				}				
			}			
		} ) );
		
		return $filtered_table_data;
		
	}
		
	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		
		return $item[$column_name];
	}

	/**
	 * Get value for data_main column.
	 *
	 */
	protected function column_data_main( $item ) {
		$url = get_home_url().$item['data_main'];
		return "<a href='$url' target='_blank'>".$item['data_main']."</a>";
	}

	/**
	 * Get value for data_secondary column.
	 *
	 */
	protected function column_data_secondary( $item ) {
		$data = maybe_unserialize($item['data_secondary']);
		if(!is_array($data)) return $data;
		foreach($data as $varnish_ip){
			echo  $varnish_ip. '<br>';
		}
	}

	/**
	 * 
	 * Get value for origin_url column.
	 *
	 */
	protected function column_origin_url( $item ) {

		// Custom purge
		$rest_route = 'wp-json/'.$this->loader->load('REST/WG_Varnish_REST_Purge')->get_api_root().'/purge-urls';
		if (strpos($item['origin_url'], $rest_route) !== false) {
			return '<a href="/wp-admin/admin.php?page=wg-varnish">Custom purge</a>';
		}

		// Edit post
		if (strpos($item['origin_url'], '/wp-admin/post.php') !== false && $item['origin_id']) {
			$post = get_post( $item['origin_id'] );
			return '<a href="/wp-admin/post.php?post='.$item['origin_id'].'&action=edit">'.$post->post_title.'</a>';
		}

		// Default
		return $item['origin_url'] . ($item['origin_id'] ? ' -> '.$item['origin_id']: '');
	}

	/**
	 * Get value for user_id column.
	 *
	 */
	protected function column_user_id( $item ) {
		if(!$item['user_id']) return '';
		return '<a href="/wp-admin/user-edit.php?user_id='.$item['user_id'].'">'.get_userdata($item['user_id'])->data->user_nicename.'</a>';
	}
	
	/**
	 * Get value for time column.
	 *
	 */
	protected function column_time( $item ) {
		return 	date("F j, Y, g:i a", strtotime($item['time']));
	}

}