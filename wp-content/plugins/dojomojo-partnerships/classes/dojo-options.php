<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class DojoOptions { 

  function initialize_dojomojo_plugin_menu() {
    add_action( 'admin_menu', [$this, 'dojomojo_menu'] );
    add_action( 'admin_init', [$this, 'register_dojo_settings']);
  }

  function dojomojo_menu() {
    add_menu_page( 'DojoMojo', 'DojoMojo', 'manage_options', 'dojomojo', [$this, 'dojomojo_options'] );
  }

  function register_dojo_settings() {
    register_setting( 'dojo-options', 'dojomojo_giveaway_slug' );
  }

  function dojomojo_options() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    // Dude PHP is so weird.... this is somehow valid syntax....
    ?>

    <div class="wrap">
      <h1>DojoMojo</h1>
      <p>Configure your giveaways page</p>

      <form method="post" action="options.php">
        <?php settings_fields( 'dojo-options' ); ?>
        <?php do_settings_sections( 'dojo-options' ); ?>

        <table class="form-table" style="width: 50%">
          <tr valign="top">
          <th scope="row">Giveaway Slug:</th>
          <td><input type="text" name="dojomojo_giveaway_slug" value="<?php echo esc_attr( get_option('dojomojo_giveaway_slug') ); ?>" style="width: 100%" placeholder="/giveaways"/></td>
          </tr>
        </table>

        <?php submit_button(); ?>
      </form>
    </div>

  <?php 
  }
}