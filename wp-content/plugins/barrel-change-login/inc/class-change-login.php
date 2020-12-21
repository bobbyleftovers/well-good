<?php

defined( 'ABSPATH' ) || die( 'Do not access this file directly' );


if ( defined( 'ABSPATH' ) && ! class_exists( 'Barrel_Change_Login' ) ) {

	class Barrel_Change_Login {
		// private $barrel_login_php;
		public $barrel_login_php;

		public function __construct() {
			global $wp_version;

			register_activation_hook( $this->basename(), array( $this, 'activate' ) );
			register_uninstall_hook( $this->basename(), array( 'Barrel_Change_Login', 'uninstall' ) );

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );

			if ( is_multisite() && ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}

			add_filter( 'plugin_action_links_' . $this->basename(), array( $this, 'plugin_action_links' ) );

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );
			add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );

			add_filter( 'site_url', array( $this, 'site_url' ), 10, 4 );
			add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
			add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );

			add_filter( 'site_option_welcome_email', array( $this, 'welcome_email' ) );

			remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
		}

		public function activate() {
			add_option( 'rwl_redirect', '1' );
			delete_option( 'rwl_admin' );
		}

		public static function uninstall() {
			delete_option( 'barrel_login_page' );
		}

		public function admin_init() {
			global $pagenow;

			add_settings_section(
				'change-wp-admin-login-section',
				__( 'Change wp-admin login', 'barrel-change-login' ),
				array( $this, 'rwl_section_desc' ),
				'permalink'
			);

			add_settings_field(
				'barrel-login-page',
				'<label for="barrel-login-page">' . __( 'Login url', 'barrel-change-login' ) . '</label>',
				array( $this, 'barrel_login_page_input' ),
				'permalink',
				'change-wp-admin-login-section'
			);

			if ( isset( $_POST['barrel_login_page'] ) && $pagenow === 'options-permalink.php' ) {
				if (
					( $barrel_login_page = sanitize_title_with_dashes( $_POST['barrel_login_page'] ) ) &&
					strpos( $barrel_login_page, 'wp-login' ) === false &&
					! in_array( $barrel_login_page, $this->forbidden_slugs() )
				) {
					if ( is_multisite() && $barrel_login_page === get_site_option( 'barrel_login_page', 'barrel-login' ) ) {
						delete_option( 'barrel_login_page' );
					} else {
						update_option( 'barrel_login_page', $barrel_login_page );
					}
				}
			}

			if ( get_option( 'rwl_redirect' ) ) {
				delete_option( 'rwl_redirect' );

				$redirect = admin_url( 'options-permalink.php#barrel-login-page-input' );

				wp_safe_redirect( $redirect );

				die;
			}
		}

		public function rwl_section_desc() {
			$out = '';

			if ( is_multisite() && is_super_admin() && is_plugin_active_for_network( $this->basename() ) ) {
				$out .= '<p>' . sprintf( __( 'To set a networkwide default, go to %s.', 'barrel-change-login' ), '<a href="' . network_admin_url( 'settings.php#barrel-login-page-input' ) . '">' . __( 'Network Settings', 'barrel-change-login' ) . '</a>' ) . '</p>';
			}

			echo $out;
		}

		public function barrel_login_page_input() {
			if ( get_option( 'permalink_structure' ) ) {
				echo '<code>' . trailingslashit( home_url() ) . '</code> <input id="barrel-login-page-input" type="text" name="barrel_login_page" value="' . $this->new_login_slug() . '">' . ( $this->use_trailing_slashes() ? ' <code>/</code>' : '' );
			} else {
				echo '<code>' . trailingslashit( home_url() ) . '?</code> <input id="barrel-login-page-input" type="text" name="barrel_login_page" value="' . $this->new_login_slug() . '">';
			}
		}

		public function admin_notices() {
			global $pagenow;

			if ( ! is_network_admin() && $pagenow === 'options-permalink.php' && isset( $_GET['settings-updated'] ) ) {
				echo '<div class="updated"><p>' . sprintf( __( 'Your login page is now here: %s. Bookmark this page!', 'barrel-change-login' ), '<strong><a href="' . $this->new_login_url() . '">' . $this->new_login_url() . '</a></strong>' ) . '</p></div>';
			}
		}

		public function plugin_action_links( $links ) {
			if ( is_network_admin() && is_plugin_active_for_network( $this->basename() ) ) {
				array_unshift( $links, '<a href="' . network_admin_url( 'settings.php#barrel-login-page-input' ) . '">' . __( 'Settings', 'barrel-change-login' ) . '</a>' );
			} elseif ( ! is_network_admin() ) {
				array_unshift( $links, '<a href="' . admin_url( 'options-permalink.php#barrel-login-page-input' ) . '">' . __( 'Settings', 'barrel-change-login' ) . '</a>' );
			}

			return $links;
		}

		public function plugins_loaded() {
			global $pagenow;

			load_plugin_textdomain( 'barrel-change-login' );

			if ( ! is_multisite() && (
					strpos( $_SERVER['REQUEST_URI'], 'wp-signup' ) !== false ||
					strpos( $_SERVER['REQUEST_URI'], 'wp-activate' ) !== false
				)
			) {
				wp_die( __( 'This feature is not enabled.', 'barrel-change-login' ) );
			}

			$request = parse_url( $_SERVER['REQUEST_URI'] );

			if ( ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ||
					untrailingslashit( $request['path'] ) === site_url( 'wp-login', 'relative' )
				) &&
				! is_admin()
			) {
				$this->barrel_login_php = true;
				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );
				$pagenow = 'index.php';
			} elseif (
				untrailingslashit( $request['path'] ) === home_url( $this->new_login_slug(), 'relative' ) || (
					! get_option( 'permalink_structure' ) &&
					isset( $_GET[ $this->new_login_slug() ] ) &&
					empty( $_GET[ $this->new_login_slug() ] )
			) ) {
				$pagenow = 'wp-login.php';
			}
		}

		public function wp_loaded() {
			global $pagenow;

			if ( is_admin() && ! is_user_logged_in() && ! defined( 'DOING_AJAX' ) ) {
				wp_safe_redirect( home_url( '/' ) );
				die;
			}

			$request = parse_url( $_SERVER['REQUEST_URI'] );

			if ( $pagenow === 'wp-login.php' &&
				$request['path'] !== $this->user_trailingslashit( $request['path'] ) &&
				get_option( 'permalink_structure' )
			) {
				wp_safe_redirect( $this->user_trailingslashit( $this->new_login_url() ) . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
				die;
			} elseif ( $this->barrel_login_php ) {
				if (
					( $referer = wp_get_referer() ) &&
					strpos( $referer, 'wp-activate.php' ) !== false &&
					( $referer = parse_url( $referer ) ) &&
					! empty( $referer['query'] )
				) {
					parse_str( $referer['query'], $referer );

					if (
						! empty( $referer['key'] ) &&
						( $result = wpmu_activate_signup( $referer['key'] ) ) &&
						is_wp_error( $result ) && (
							$result->get_error_code() === 'already_active' ||
							$result->get_error_code() === 'blog_taken'
					) ) {
						wp_safe_redirect( $this->new_login_url() . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
						die;
					}
				}

				$this->wp_template_loader();
			} elseif ( $pagenow === 'wp-login.php' ) {
				global $error, $interim_login, $action, $user_login;
				@require_once ABSPATH . 'wp-login.php';
				die;
			}
		}

		public function site_url( $url, $path, $scheme, $blog_id ) {
			return $this->filter_wp_login_php( $url, $scheme );
		}

		public function wp_redirect( $location, $status ) {
			return $this->filter_wp_login_php( $location );
		}

		public function filter_wp_login_php( $url, $scheme = null ) {
			if ( strpos( $url, 'wp-login.php' ) !== false ) {
				if ( is_ssl() ) {
					$scheme = 'https';
				}

				$args = explode( '?', $url );

				if ( isset( $args[1] ) ) {
					parse_str( $args[1], $args );
					$url = add_query_arg( $args, $this->new_login_url( $scheme ) );
				} else {
					$url = $this->new_login_url( $scheme );
				}
			}

			return $url;
		}

		public function welcome_email( $value ) {
			return $value = str_replace( 'wp-login.php', trailingslashit( get_site_option( 'barrel_login_page', 'barrel-login' ) ), $value );
		}

		public function forbidden_slugs() {
			$wp = new WP();
			return array_merge( $wp->public_query_vars, $wp->private_query_vars );
		}

		private function basename() {
			return plugin_basename( __FILE__ );
		}

		private function path() {
			return trailingslashit( dirname( __FILE__ ) );
		}

		private function use_trailing_slashes() {
			return '/' === substr( get_option( 'permalink_structure' ), -1, 1 );
		}

		private function user_trailingslashit( $string ) {
			return $this->use_trailing_slashes() ? trailingslashit( $string ) : untrailingslashit( $string );
		}

		private function wp_template_loader() {
			global $pagenow;

			$pagenow = 'index.php';

			if ( ! defined( 'WP_USE_THEMES' ) ) {
				define( 'WP_USE_THEMES', true );
			}

			wp();

			if ( $_SERVER['REQUEST_URI'] === $this->user_trailingslashit( str_repeat( '-/', 10 ) ) ) {
				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/wp-login-php/' );
			}

			require_once( ABSPATH . WPINC . '/template-loader.php' );

			die;
		}

		private function new_login_slug() {
			if (
				( $slug = get_option( 'barrel_login_page' ) ) || (
					is_multisite() &&
					is_plugin_active_for_network( $this->basename() ) &&
					( $slug = get_site_option( 'barrel_login_page', 'barrel-login' ) )
				) ||
				( $slug = 'login' )
			) {
				return $slug;
			}
		}

		public function new_login_url( $scheme = null ) {
			if ( get_option( 'permalink_structure' ) ) {
				return $this->user_trailingslashit( home_url( '/', $scheme ) . $this->new_login_slug() );
			} else {
				return home_url( '/', $scheme ) . '?' . $this->new_login_slug();
			}
		}

	}

	new Barrel_Change_Login();
}
