<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WG_Redirections
 * @subpackage WG_Redirections/includes/admin
 * @author     Barrel
 */
class WG_Redirections_Admin_Scripts {

	use WG_Redirections_Core, WG_Redirections_Admin_Data;


	/**
	 * Register the scripts and stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$page = str_replace($this->plugin_name.'-','',$_GET['page']);

		if($page === $this->plugin_name) $page = 'main';

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css' );

		//JS
		$js_per_page = array(
			'main' => array(
				'chunk-vendors',
				'quick-edit'
			),
			'importer' => array(
				'chunk-vendors',
				'importer'
			),
			'maintenance' => array(
				'chunk-vendors',
				'maintenance'
			),
			'sitemap' => array(
				'chunk-vendors',
				'sitemap'
			)
		);

		//Localize
		$localize = array(
			'chunk-vendors' => array(
				'redirection_vars' => array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				),
				'wg_redirections_data' => array(
					'possible_types' => $this->get_possible_types()
				)
			)
		);
		//CSS
		$css_per_page = array(
			'importer' => array(
				'importer'
			),
			'sitemap' => array(
				'sitemap'
			),
			'maintenance' => array(
				'maintenance'
			),
		);

		if(!isset($js_per_page[$page]) && !isset($css_per_page[$page])) return;

		//URI
		$scripts_uri = $this->get_scripts_uri();

		//JS
		if(isset($js_per_page[$page])){
			foreach($js_per_page[$page] as $script){

				$script_name = $this->plugin_name . '-'.$script;
				
				//js
				wp_enqueue_script( $script_name, $scripts_uri  . 'js/'.$script.'.js', [], $this->version, false );
	
				//localize
				if(isset($localize[$script])){
					foreach($localize[$script] as $var_name => $var_value){
						wp_localize_script(
							$script_name,
							$var_name,
							$var_value
						);
					}
				}
			}
		}

		//CSS
		if(isset($css_per_page[$page])){
			foreach($css_per_page[$page] as $script){
				wp_enqueue_style( $this->plugin_name . '-'.$script, $scripts_uri . 'css/'.$script.'.css', [], $this->version, 'all' );
			}
		}
	}
}
