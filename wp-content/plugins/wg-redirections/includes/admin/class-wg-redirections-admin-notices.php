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
class WG_Redirections_Admin_Notices {

    use WG_Redirections_Core;

    public static function add($notice){

        set_transient(get_current_user_id() . 'notice', $notice);
    }

    /**
    * Show notice admin screen
    */
    public function show_admin_notice(){
        global $pagenow;

        if ( $pagenow == 'admin.php' && $_GET['page'] == $this->plugin_name ) {
            $notice = get_transient( get_current_user_id().'notice' );

            if (!empty($notice)) {
                delete_transient(get_current_user_id() . 'notice');

                if (isset($notice['message'])) {
                    echo '<div class="updated notice notice-success is-dismissible">
                         <p>' . $notice['message'] . '</p>
                    </div>';
                } else {
                    echo '<div class="notice notice-warning is-dismissible">
                        <p>' . $notice['error'] . '</p>
                    </div>';
                }
            }
        }
    }
	
}
