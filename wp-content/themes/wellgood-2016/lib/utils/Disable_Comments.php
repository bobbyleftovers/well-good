<?php

namespace WG\Utils;

class Disable_Comments {
	const DB_VERSION         = 6;
	private static $instance = null;
	private $options;
	private $networkactive;
	private $modified_types = array();


	function __construct() {
		add_action( 'widgets_init', array( $this, 'disable_rc_widget' ) );
		add_filter( 'wp_headers', array( $this, 'filter_wp_headers' ) );
		add_action( 'template_redirect', array( $this, 'filter_query' ), 9 );   // before redirect_canonical.

		// Admin bar filtering has to happen here since WP 3.6.
		add_action( 'template_redirect', array( $this, 'filter_admin_bar' ) );
		add_action( 'admin_init', array( $this, 'filter_admin_bar' ) );

		// Disable Comments REST API Endpoint
		add_filter( 'rest_endpoints', array( $this, 'filter_rest_endpoints' ) );

		// Disable "Latest comments" block in Gutenberg.
		add_action( 'enqueue_block_editor_assets', array( $this, 'disable_comments_script') );
	}

	/**
	 * Remove the X-Pingback HTTP header
	 */
	public function filter_wp_headers( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Issue a 403 for all comment feed requests.
	 */
	public function filter_query() {
		if ( is_comment_feed() ) {
			wp_die( __( 'Comments are closed.', 'disable-comments' ), '', array( 'response' => 403 ) );
		}
	}

	/**
	 * Remove comment links from the admin bar.
	 */
	public function filter_admin_bar() {
		if ( is_admin_bar_showing() ) {
			// Remove comments links from admin bar.
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
			if ( is_multisite() ) {
				add_action( 'admin_bar_menu', array( $this, 'remove_network_comment_links' ), 500 );
			}
		}
	}

	/**
	 * Remove the comments endpoint for the REST API
	 */
	public function filter_rest_endpoints( $endpoints ) {
		unset( $endpoints['comments'] );
		return $endpoints;
  }
  
	/**
	 * Enqueues scripts
	 */
	public function disable_comments_script() {
		wp_enqueue_script( 'disable-comments-gutenberg', plugin_dir_url( __FILE__ ) . 'assets/disable-comments.js', array(), false, true );
	}

	/**
	 * Return context-aware settings page URL
	 */
	private function settings_page_url() {
		$base = $this->networkactive ? network_admin_url( 'settings.php' ) : admin_url( 'options-general.php' );
		return add_query_arg( 'page', 'disable_comments_settings', $base );
	}

	/**
	 * Return context-aware tools page URL
	 */
	private function tools_page_url() {
		$base = $this->networkactive ? network_admin_url( 'settings.php' ) : admin_url( 'tools.php' );
		return add_query_arg( 'page', 'disable_comments_tools', $base );
	}

	public function setup_notice() {
		if ( strpos( get_current_screen()->id, 'settings_page_disable_comments_settings' ) === 0 ) {
			return;
		}
		$hascaps = $this->networkactive ? is_network_admin() && current_user_can( 'manage_network_plugins' ) : current_user_can( 'manage_options' );
		if ( $hascaps ) {
			echo '<div class="updated fade"><p>' . sprintf( __( 'The <em>Disable Comments</em> plugin is active, but isn\'t configured to do anything yet. Visit the <a href="%s">configuration page</a> to choose which post types to disable comments on.', 'disable-comments' ), esc_attr( $this->settings_page_url() ) ) . '</p></div>';
		}
	}

	public function filter_admin_menu() {
		global $pagenow;

		if ( $pagenow == 'comment.php' || $pagenow == 'edit-comments.php' ) {
			wp_die( __( 'Comments are closed.', 'disable-comments' ), '', array( 'response' => 403 ) );
		}

		remove_menu_page( 'edit-comments.php' );

		if ( ! $this->discussion_settings_allowed() ) {
			if ( $pagenow == 'options-discussion.php' ) {
				wp_die( __( 'Comments are closed.', 'disable-comments' ), '', array( 'response' => 403 ) );
			}

			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		}
	}

	public function filter_existing_comments( $comments, $post_id ) {
		$post = get_post( $post_id );
		return ( $this->options['remove_everywhere'] || $this->is_post_type_disabled( $post->post_type ) ) ? array() : $comments;
	}

	public function filter_comment_status( $open, $post_id ) {
		$post = get_post( $post_id );
		return ( $this->options['remove_everywhere'] || $this->is_post_type_disabled( $post->post_type ) ) ? false : $open;
	}

	public function filter_comments_number( $count, $post_id ) {
		$post = get_post( $post_id );
		return ( $this->options['remove_everywhere'] || $this->is_post_type_disabled( $post->post_type ) ) ? 0 : $count;
	}

	public function disable_rc_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		/**
		 * The widget has added a style action when it was constructed - which will
		 * still fire even if we now unregister the widget... so filter that out
		 */
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	private function discussion_settings_allowed() {
		if ( defined( 'DISABLE_COMMENTS_ALLOW_DISCUSSION_SETTINGS' ) && DISABLE_COMMENTS_ALLOW_DISCUSSION_SETTINGS == true ) {
			return true;
		}
	}
}
