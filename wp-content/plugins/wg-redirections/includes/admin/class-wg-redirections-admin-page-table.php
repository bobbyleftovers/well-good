<?php

/**
 * Admin Menu
 */
class WG_Redirections_Admin_Page_Table extends WG_Redirections_Table {

	use WG_Redirections_Core, WG_Redirections_Admin_Data;

	public $filter_start_date = null;
	public $filter_end_date = null;

	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" name="ids[]">',
			'source_uri' => 'Source URI',
			'target_uri' => 'Target URI',
			'http_response' => 'HTTP Response',
			'type' => 'Type',
			'counter' => 'Total hits',
			'last_visit' => 'Last hit',
			'edit_time' => 'Last edit',
			'is_active' => 'Active',
			'add_to_sitemap' => 'Add to sitemap',
		);
		return $columns;
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
	public function get_sortable_columns() {

		/*
		 * actual sorting still needs to be done by prepare_items.
		 * specify which columns should have the sort icon.
		 *
		 * key => value
		 * column name_in_list_table => columnname in the db
		 */
		$sortable_columns = array (
			'source_uri'=> array('source_uri', false),
			'target_uri'=> array('target_uri', false),
			'last_visit'=> array('last_visit', false),
			'edit_time'=> array('edit_time', false),
			'counter'=> array('counter', false),
			'type'=> array('type', false),
			'http_response'=> array('http_response', false),
			'is_active'=> array('is_active', false),
			'add_to_sitemap'=> array('add_to_sitemap', false)
		);

		return $sortable_columns;
	}

	/**
	 *  Empty table
	 */

	public function no_items() {
		echo 'No redirections avaliable.';
	}

	/**
	 * Show column checkbox
	 * @param object $item
	 * @return string|void
	 */
	public function column_cb($item) {
		return sprintf(
      '<input type="checkbox" name="%1$s[]" value="%2$s" />',
      'ids',
      $item['id']
		);
	}

	/**
	 * Get Bulk Dropdown action
	 * @return array
	 */
	public function get_bulk_actions() {
		$post_status = ( !empty($_REQUEST['post_status']) ? $_REQUEST['post_status'] : 'all');
		$status = array('published', 'pending', 'all');

		if (in_array($post_status, $status)) {
			$actions['trash'] = __('Move to Trash', 'wg-redirections');
		} else {
			$actions = array(
				'untrash' => __('Restore', 'wg-redirections'),
				'delete' => __('Delete Permanently', 'wg-redirections'),
			);
		}

		return $actions;
	}

	/**
	 * Process action bulk dropdown
	 */
	public function process_bulk_action() {
		$action = $this->current_action();
		$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : array();

		switch ($action) {
			case 'trash':
				if ( !empty($ids) ) {
					foreach ($ids as $id) {
						$where = array('id' => $id);
						$args = array( 'is_active' => 3 );
						$this->update_record($args, $where);
					}
					wp_redirect('admin.php?page=wg-redirections');
				}
				break;
			case 'untrash':
				if ( !empty($ids) ) {
					foreach ($ids as $id) {
						$where = array('id' => $id);
						$args = array( 'is_active' => 1 );
						$this->update_record($args, $where);
					}
					wp_redirect('admin.php?page=wg-redirections');
				}
				break;
			case 'delete':
				if ( !empty($ids) ) {
					foreach ($ids as $id) {
						$where = array('id' => $id);
						$this->delete_record($where);
					}
					wp_redirect('admin.php?page=wg-redirections');
				}
				break;
			default:
				break;
		}
	}

	/**
	 * Get Views
	 * @return array|mixed
	 */
	public function get_views(){
		$current = ( !empty($_REQUEST['post_status']) ? $_REQUEST['post_status'] : 'all');

		// All link
		$class = ($current == 'all' ? ' class="current"' : '');
		$all_url = remove_query_arg( array( 'post_status', 's', 'paged', 'alert', 'user' ) );
		$views['all'] = "<a href='{$all_url }' {$class} >".__('All', 'wg-redirections')." <span class=\"count\">(" . $this->num_rows('all') . ")</span></a>";

		// Published link
		$published_url = add_query_arg('post_status', 'published');
		$class = ($current == 'published' ? ' class="current"' : '');
		$views['published'] = "<a href='{$published_url}' {$class} >".__('Active ', 'wg-redirections')." <span class=\"count\">(" . $this->num_rows(1) . ")</span></a>";

		// Pending link
		$pending_url = add_query_arg('post_status', 'pending');
		$class = ($current == 'pending' ? ' class="current"' : '');
		$views['pending'] = "<a href='{$pending_url}' {$class} >".__('Unactive ', 'wg-redirections')." <span class=\"count\">(" . $this->num_rows(0) . ")</span></a>";

		// Trash link
		$trash_url = add_query_arg('post_status', 'trash');
		$class = ($current == 'trash' ? ' class="current"' : '');
		$views['trash'] = "<a href='{$trash_url}' {$class} >".__('Trash ', 'wg-redirections')." <span class=\"count\">(" . $this->num_rows(3) . ")</span></a>";

		return $views;
	}

	/**
	 * Delete records from database table
	 * @param $condition
	 * @return mixed
	 */
	public function delete_record($condition) {
		global $wpdb;

		$sql_table = $this->get_table_name('');

		return $wpdb->delete($sql_table, $condition);
	}

	/**
	 * Update records from database table
	 * @param $args
	 * @param $where
	 * @return mixed
	 */
	public function update_record($args, $where) {
		global $wpdb;

		$sql_table = $this->get_table_name('');

		return $wpdb->update($sql_table, $args, $where);
	}

	/**
	 * Prepares the list of items for displaying.
	 *
	 * Query, filter data, handle sorting, and pagination, and any other data-manipulation required prior to rendering
	 *
	 * @since   1.0.0
	 */
	public function prepare_items() {
		//Retrieve $current for use in query to get items.
		$post_status = ( isset($_REQUEST['post_status']) ? $_REQUEST['post_status'] : 'all');

		// Set the date filter
		$this->set_date_filter();

		$_SERVER['REQUEST_URI'] = remove_query_arg('_wp_http_referer', $_SERVER['REQUEST_URI']);

		$this->_column_headers = $this->get_column_info();

		// fetch table data
		$table_data = $this->fetch_table_data($post_status);

		// required for pagination
		$user = get_current_user_id();
		$screen = get_current_screen();
		$screen_option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $screen_option, true);

		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}

		$current_page = $this->get_pagenum();
		if (1 < $current_page) {
			$offset = ($current_page - 1) * $per_page;
		} else {
			$offset = 0;
		}

		$search = '';
		if (!empty($_REQUEST['s'])) {
			$search = $_REQUEST['s'];
		}

		if (empty($table_data)) {
			$items = array();
			$total_items = 0;
			$total_pages = 0;
		} else {
			$data_search = array();
			if (!empty($search)) {
				foreach ($table_data as $row) {
					if (!empty($search) && strpos($row['source_uri'], $search) !== false) {
						$data_search[] = $row;
					}
				}
				$results = $data_search;
			} else {
				$results = $table_data;
			}
			$total_items = count($results);
			$total_pages = ceil( $total_items / $per_page );
			$items = array_slice( $results, $offset, $per_page );
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->process_bulk_action();

		// provide the ordered data to the List Table.
		// we need to manually slice the data based on the current pagination.
		$this->items = $items;

		// set the pagination arguments
		$this->set_pagination_args( array (
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => $total_pages
		) );
	}

	/**
	 * Fetch table data from the WordPress database.
	 * @since 1.3.0
	 * @param $post_status
	 * @return array|object|null
	 */
	public function fetch_table_data($post_status) {
		$where = 'WHERE is_active IN (0,1)';

		if ($post_status == 'pending') {
			$where = "WHERE is_active = 0";
		} elseif ($post_status == 'published') {
			$where = "WHERE is_active = 1";
		} elseif ($post_status == 'trash') {
			$where = "WHERE is_active = 3";
		}

		if ( !empty($this->filter_start_date) ) {
			$where .= " AND edit_time >= '{$this->filter_start_date} 00:00:00'";
		}

		if ( !empty($this->filter_end_date) ) {
			$where .= " AND edit_time <= '{$this->filter_end_date} 23:59:59'";
		}

		$orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
		$order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';

		$sql_table = $this->get_table_name('');
		$sql_query = "SELECT * FROM `{$sql_table}` {$where} ORDER BY {$orderby} {$order}";

		return $this->get_results($sql_query, ARRAY_A);
	}

	/**
	 * Get num rows in table
	 * @param $is_active
	 * @return string|null
	 */
	public function num_rows($is_active = '') {
		global $wpdb;

		$where = " WHERE is_active IN (0,1)";
		if ($is_active !== 'all') {
			$where = " WHERE is_active = " . $is_active;
		}

		$table_name = $this->get_table_name('');
		$count_query = "SELECT count(*) FROM `{$table_name}` {$where}";
		$num = $wpdb->get_var($count_query);

		return $num;
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
	 * Get value for uri columns.
	 */
	protected function column_source_uri( $item ) {
		$page = wp_unslash( $_REQUEST['page'] );
		$post_status = ( !empty($_REQUEST['post_status']) ? $_REQUEST['post_status'] : 'all');
		$status = array('published', 'pending', 'all');
		$query_args = array();
		$format_html = '';
		if (in_array($post_status, $status)) {
			// Build edit row action.
			$query_args = array(
				'page'   => $page,
				'action' => 'edit',
				'id'  => $item['id'],
			);

			$actions['edit'] = sprintf(
			'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( add_query_arg( $query_args, 'admin.php' ), 'edit' ) ),
				_x( 'Edit', 'List table row action', 'wp-redirections' )
			);

			// Build visit url row action.
			$actions['test'] = sprintf(
				'<a href="'.get_home_url().$item['source_uri'].'" target="_blank">Test url</a>'
			);

			// Build trash row action.
			$trash_query_args = array(
				'page'   => $page,
				'action' => 'trash',
				'ids'  => array($item['id']),
			);

			$actions['trash'] = sprintf(
			'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( add_query_arg( $trash_query_args, 'admin.php' ), 'trash' ) ),
				_x( 'Trash', 'List table row action', 'wp-redirections' )
			);

			$actions['inline hide-if-no-js'] = sprintf(
				'<button type="button" class="button-link editinline js-quickview-edit" data-id="%s" aria-label="%s" aria-expanded="false">%s</button>',
				$item['id'],
				esc_attr( sprintf( __( 'Quick edit &#8220;%s&#8221; inline', 'wp-redirections' ), $item['source_uri'] ) ),
				__( 'Quick&nbsp;Edit', 'wp-redirections' )
			);

			$format_html = '<a class="js-column-source-uri" href="%1$s">%2$s</a>%3$s';
		} else {
			// Build trash row action.
			$query_args = array(
				'page'   => $page,
				'action' => 'untrash',
				'ids'  => array($item['id']),
			);

			$actions['untrash'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( add_query_arg( $query_args, 'admin.php' ), 'untrash' ) ),
				_x( 'Restore', 'List table row action', 'wp-redirections' )
			);

			// Build delete row action.
			$delete_query_args = array(
				'page'   => $page,
				'action' => 'delete',
				'ids'  => array($item['id']),
			);

			$actions['delete'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( wp_nonce_url( add_query_arg( $delete_query_args, 'admin.php' ), 'delete' ) ),
				_x( 'Delete Permanently', 'List table row action', 'wp-redirections' )
			);
			$format_html = '<p data-href="%1$s">%2$s</p>%3$s';
		}

		return sprintf(
			$format_html,
			esc_url( wp_nonce_url( add_query_arg( $query_args, 'admin.php' ), 'edit' ) ),
			$item['source_uri'],
			$this->row_actions( $actions )
		);
	}

	protected function column_target_uri( $item ) {
		return 	$this->column_uri($item['target_uri']);
	}

	protected function column_uri( $item ) {
		$post_status = ( !empty($_REQUEST['post_status']) ? $_REQUEST['post_status'] : 'all');
		$status = array('published', 'pending', 'all');
		if (in_array($post_status, $status)) {
			return "<a class='js-column-target-uri' href='{$item}' target='_blank'>{$item}</a>";
		} else {
			return "<p>{$item}</p>";
		}
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
	 * Get value for time columns.
	 *
	 */
	protected function column_last_visit( $item ) {
		$time = '<i style="opacity:0.4">Never</i>';
		if ($item['last_visit'] !== '0000-00-00 00:00:00') {
		  $time = $this->column_time($item['last_visit']);
		}
		return $time;
	}

	protected function column_edit_time( $item ) {
		return 	$this->column_time($item['edit_time']);
	}

	protected function column_time( $item ) {
		return 	'<p class="js-column-last-edit">' . date("F j, Y, g:i a", strtotime($item)) .'</p>';
	}

	protected function column_http_response( $item ) {
		return 	'<p class="js-column-http-response">' . $item['http_response'] .'</p>';
	}

	/**
	 * Is active
	 *
	 */
	protected function column_is_active( $item ) {
		if($item['is_active']) {
			return '<span class="js-is-active" style="overflow: hidden; background:lime; display:inline-block; width: 12px; height: 12px; margin-top: 8px; text-indent:-99em; border-radius: 50%">Yes</span>';
		} else {
			return '<span class="js-is-active" style="overflow: hidden; background:#FF6347; display:inline-block; width: 12px; height: 12px; margin-top: 8px; text-indent:-99em; border-radius: 50%">No</span>';
		}
	}

	/**
	 * Add to sitemap
	 *
	 */
	protected function column_add_to_sitemap( $item ) {
		if(!empty($item['add_to_sitemap'])) {
			return 'Yes';
		} else {
			return 'No';
		}
	}

	/**
	 * Type
	 *
	 */
	protected function column_type( $item ) {
		if(!$item['type'] || $item['type'] === '') return '<p class="js-column-type"><span style="opacity:0.4; font-style: italic;">None</span></p>';
		$key = array_search($item['type'], array_column($this->get_possible_types(), 'value'));
		return '<p class="js-column-type">' . $this->get_possible_types()[$key]['label'] . '</p>';
	}

	/**
	 * Outputs the hidden row displayed when inline editing
	 * @since 3.1.0
	 * @global string $mode List table view mode.
	 */
	public function inline_edit() {
		include dirname( __FILE__ ) . '/views/wg-redirections-quick-edit.php';
  	}

	/**
	 * Set the correct date filter
	 * $_POST values should always overwrite $_GET values
	 */
	public function set_date_filter() {

		if ( !empty( $_GET['action'] ) && $_GET['action'] == 'clear' ) {
			wp_redirect('admin.php?page=wg-redirections');
		}

		if ( !empty( $_GET['start_date'] ) ) {
			$this->filter_start_date =  sanitize_text_field( $_GET['start_date'] );
		}

		if ( !empty( $_GET['end_date'] ) ) {
			$this->filter_end_date = sanitize_text_field( $_GET['end_date'] );
		}
	}

	/**
	 * Show the time views, date filters
	 */
	public function extra_tablenav( $which ) {
		?>
		<div id="date-filters" class="alignleft actions date-filters">
			<div class="date-filters__wrapper">
				<input type="text" id="start-date" name="start_date" class="datepicker" value="<?php echo $this->filter_start_date;?>" placeholder="<?php _e( 'Start Date', 'wg-redirections' ); ?>" />
				<input type="text" id="end-date" name="end_date" class="datepicker" value="<?php echo $this->filter_end_date;?>" placeholder="<?php _e( 'End Date', 'wg-redirections' ); ?>" />
				<input type="submit" class="button button-secondary" name="filter_action" value="<?php _e( 'Filter', 'wg-redirections' ); ?>"/>
				<?php if( !empty( $this->filter_start_date ) || !empty( $this->filter_end_date ) ) : ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'clear' ) ) ); ?>" class="button button-secondary"><?php _e( 'Clear', 'wg-redirections' ); ?></a>
				<?php endif; ?>
			</div>

			<?php if( !empty( $_GET['status'] ) ) : ?>
				<input type="hidden" name="status" value="<?php echo esc_attr( sanitize_text_field( $_GET['status'] ) ); ?>"/>
			<?php endif; ?>
		</div>

		<script>
			jQuery( function($) {
				var from = $('input[name="start_date"]'),
				to = $('input[name="end_date"]');

				$( 'input[name="start_date"], input[name="end_date"]' ).datepicker( {dateFormat : "yy-mm-dd"} );
				from.on( 'change', function() {
					to.datepicker( 'option', 'minDate', from.val() );
					from.attr('value', from.val());
				});

				to.on( 'change', function() {
					from.datepicker( 'option', 'maxDate', to.val() );
					to.attr('value', to.val());
				});
			});
		</script>

		<?php
	}
}
