<?php

/**
 * Admin Menu
 */
class WG_Redirections_Admin_Pages {

    use WG_Redirections_Core, WG_Redirections_Admin_Data;

    private $action;
    private $id;

    function __construct(){

        $this->admin_page = '/wp-admin/admin.php?page='. $this->plugin_name;
        $this->action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $this->id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($this->action) {
            case 'edit':
            case 'new':
                break;
            default:
                add_filter('set-screen-option', array($this,'set_option'), 10, 3);
                break;
        }

    }

    /**
    * Screen options
    */
    public function screen_option() {
        $option = 'per_page';
        $args   = [
            'label'   => __('Number of items per page:', 'wg-redirections'),
            'default' => 20,
            'option'  => 'rows_per_page'
        ];

        add_screen_option( $option, $args );
    }

    /**
    * Filter manage screen id columns
    */
    public function screen_columns( $array ) {
        $array = array(
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
        return $array;
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {

        /** Top Menu **/
        $hook = add_menu_page( __(
            'Redirections', $this->plugin_name ),
            __( 'Redirections', $this->plugin_name ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'plugin_page' ),
            'dashicons-randomize',
            null
        );

        add_action("load-{$hook}", array($this, 'screen_option'));
        add_filter( "manage_{$hook}_columns", array($this, 'screen_columns'), 10, 2 );

        add_submenu_page(
            $this->plugin_name,
            __( 'Redirections', $this->plugin_name ),
            __( 'Redirections', $this->plugin_name ),
            'manage_options',
            $this->plugin_name, array( $this, 'plugin_page' )
          );

        add_submenu_page(
            $this->plugin_name,
            __( 'Add Redirection', $this->plugin_name ),
            __( '<span id="redirection-sub-menu">Add Redirection</span>', $this->plugin_name ),
            'manage_options',
            'admin.php?page=wg-redirections&action=new'
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Import', $this->plugin_name ),
            __( 'Import', $this->plugin_name ),
            'manage_options',
            $this->plugin_name.'-importer',
            array( $this, 'importer_page' )
        );

        add_submenu_page(
            $this->plugin_name,
            __( 'Maintenance', $this->plugin_name ),
            __( 'Maintenance', $this->plugin_name ),
            'manage_options',
            $this->plugin_name.'-maintenance',
            array( $this, 'maintenance_page' )
        );

        /* add_submenu_page(
            $this->plugin_name,
            __( 'Sitemap', $this->plugin_name ),
            __( 'Sitemap', $this->plugin_name ),
            'manage_options',
            $this->plugin_name.'-sitemap',
            array( $this, 'sitemap_page' )
        ); */

        add_action( 'in_admin_footer', array( $this, 'highlight_sub_menu_item' ) );
    }

   /**
    * Highlight sub menu item
    */
    public function highlight_sub_menu_item() {
        if( !empty( $_GET['page'] ) && !empty($_GET['action']) && 'new' == $_GET['action'] ) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    var reference = $('#redirection-sub-menu').parent().parent();
                    if (reference) {
                        // add highlighting to our custom submenu
                        reference.addClass('current');
                        //remove highlighting class from the default menu
                        reference.parent().find('.wp-first-item').removeClass('current');
                    }
                });
            </script>
            <?php
        }
    }

    /**
     * Handles the plugin pages
     *
     * @return void
     */
    public function plugin_page() {

        include_once  dirname( __FILE__ ) . '/views/wg-redirections-js-vars.php';

        switch ($this->action) {

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/wg-redirections-edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/wg-redirections-edit.php';
                break;

            case 'trash':
                WG_Redirections_Admin_Notices::add( array( 'message' => 'Redirection deleted' ));
            default:
                $table = $this->loader->load('Admin/WG_Redirections_Admin_Page_Table');
                $table->prepare_items();
                $template = dirname( __FILE__ ) . '/views/wg-redirections-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handles the importer addon
     *
     * @return void
     */

    public function importer_page() {
        include_once  dirname( __FILE__ ) . '/views/wg-redirections-js-vars.php';
        include dirname( __FILE__ ) . '/views/wg-redirections-importer.php';
    }

    /**
     * Handles the maintenance addon
     *
     * @return void
     */

    public function maintenance_page() {
        include_once  dirname( __FILE__ ) . '/views/wg-redirections-js-vars.php';
        include dirname( __FILE__ ) . '/views/wg-redirections-maintenance.php';
    }
    
    /**
     * Handles the sitemap addon
     *
     * @return void
     */

    public function sitemap_page() {
        include_once  dirname( __FILE__ ) . '/views/wg-redirections-js-vars.php';
        include dirname( __FILE__ ) . '/views/wg-redirections-sitemap.php';
    }

   /**
    * Set length option
    * @since    1.0.0
    */
    function set_option($status, $option, $value) {
        if ( 'rows_per_page' == $option ) return $value;
        return $status;
    }

}
