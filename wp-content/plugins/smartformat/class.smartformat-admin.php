<?php

define('SMARTFORMAT__ADMIN_PAGE', 'smartformat');
define('SMARTFORMAT__ADMIN_GROUP', 'smartformat');
define('SMARTFORMAT__ADMIN_SECTION_CHANNEL', 'channel');
define('SMARTFORMAT__ADMIN_SECTION_ITEM', 'item');

class SmartFormatAdmin {
  public function __construct() {
    add_action('admin_init', array($this, 'init'));
    add_action('admin_menu', array($this, 'init_menu'));
  }

  public function init() {
    // Channel
    add_settings_section(SMARTFORMAT__ADMIN_SECTION_CHANNEL, __('Channel'), '', SMARTFORMAT__ADMIN_PAGE);

    add_settings_field(SMARTFORMAT__LOGO_ATTACHMENT_ID, __('Logo Image'), array($this, 'render_logo_image'), SMARTFORMAT__ADMIN_PAGE, SMARTFORMAT__ADMIN_SECTION_CHANNEL);
    register_setting(
      SMARTFORMAT__ADMIN_GROUP,
      SMARTFORMAT__LOGO_ATTACHMENT_ID,
      array($this, 'sanitize_logo_image')
    );

    add_settings_field(SMARTFORMAT__TTL, __('Time To Live (TTL)'), array($this, 'render_ttl'), SMARTFORMAT__ADMIN_PAGE, SMARTFORMAT__ADMIN_SECTION_CHANNEL);
    register_setting(
      SMARTFORMAT__ADMIN_GROUP,
      SMARTFORMAT__TTL,
      array($this, 'sanitize_ttl')
    );

    // Item
    add_settings_section(SMARTFORMAT__ADMIN_SECTION_ITEM, __('Item'), '', SMARTFORMAT__ADMIN_PAGE);

    add_settings_field(SMARTFORMAT__ANALYTICS, __('Analytics Code'), array($this, 'render_analytics'), SMARTFORMAT__ADMIN_PAGE, SMARTFORMAT__ADMIN_SECTION_ITEM);
    register_setting(
      SMARTFORMAT__ADMIN_GROUP,
      SMARTFORMAT__ANALYTICS,
      array($this, 'sanitize_analytics')
    );

    add_settings_field(SMARTFORMAT__ADCONTENT, __('Ad Content'), array($this, 'render_adcontent'), SMARTFORMAT__ADMIN_PAGE, SMARTFORMAT__ADMIN_SECTION_ITEM);
    register_setting(
      SMARTFORMAT__ADMIN_GROUP,
      SMARTFORMAT__ADCONTENT,
      array($this, 'sanitize_adcontent')
    );

    add_settings_field(SMARTFORMAT__SPONSORED_LINKS, __('Sponsored Links'), array($this, 'render_sponsored_links'), SMARTFORMAT__ADMIN_PAGE, SMARTFORMAT__ADMIN_SECTION_ITEM);
    register_setting(
      SMARTFORMAT__ADMIN_GROUP,
      SMARTFORMAT__SPONSORED_LINKS,
      array($this, 'sanitize_sponsored_links')
    );
  }

  public function init_menu() {
    add_options_page(__('SmartFormat Settings'), __('SmartFormat'), 'manage_options', SMARTFORMAT__ADMIN_PAGE, array($this, 'render'));
		add_filter('plugin_action_links_' . plugin_basename(SMARTFORMAT__PLUGIN_DIR . '/smartformat.php'), array($this, 'render_plugin_action_links'));
  }

  public function render() {
    wp_enqueue_media();

    $mediaSelect = array(
      'selectOrUploadMedia' => __('Select or Upload Media')
    );
    wp_register_script('admin-media-select', plugin_dir_url(__FILE__) . 'admin-media-select.js', array('jquery', 'admin-sponsored-links'));
    wp_localize_script('admin-media-select', 'mediaSelect', $mediaSelect);
    wp_enqueue_script('admin-media-select');

    load_template(SMARTFORMAT__PLUGIN_DIR . '/admin.php');
  }

  public function render_plugin_action_links($links) {
    array_unshift($links, '<a href="options-general.php?page=' . SMARTFORMAT__ADMIN_PAGE . '">' . __('Settings') . '</a>'); 
    return $links;
  }

  public function render_logo_image() {
    load_template(SMARTFORMAT__PLUGIN_DIR . '/admin-logo-image.php');
  }

  public function render_ttl() {
    echo '<input name="' . SMARTFORMAT__TTL . '" type="number" step="1" min="1" id="' . SMARTFORMAT__TTL . '" value="' . esc_attr(get_option(SMARTFORMAT__TTL)) . '" class="small-text" /> ' . __('minutes');
  }

  public function render_analytics() {
    // TODO: fill out the description
    // echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
    echo '<textarea name="' . SMARTFORMAT__ANALYTICS . '" rows="10" cols="50" id="' . SMARTFORMAT__ANALYTICS . '" class="large-text code">' . esc_textarea(get_option(SMARTFORMAT__ANALYTICS)) . '</textarea>';
  }

  public function render_adcontent() {
    // TODO: fill out the description
    // echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
    echo '<textarea name="' . SMARTFORMAT__ADCONTENT . '" rows="10" cols="50" id="' . SMARTFORMAT__ADCONTENT . '" class="large-text code">' . esc_textarea(get_option(SMARTFORMAT__ADCONTENT)) . '</textarea>';
  }

  public function render_sponsored_links() {
    load_template(SMARTFORMAT__PLUGIN_DIR . '/admin-sponsored-links.php');
  }

  public function sanitize_logo_image($input) {
    return intval($input);
  }

  public function sanitize_ttl($input) {
    $options = array(
      'options' => array(
          'min_range' => 1,
          'max_range' => 1440
      ),
      'flags' => FILTER_FLAG_ALLOW_OCTAL
    );

    if (filter_var($input, FILTER_VALIDATE_INT, $options) !== false) {
      return intval($input);
    }

    add_settings_error(
      SMARTFORMAT__ADMIN_PAGE,
      SMARTFORMAT__TTL,
      __('Invalid TTL (1 - 1440)')
    );
    return $input;
  }

  public function sanitize_analytics($input) {
    return force_balance_tags($input);
  }

  public function sanitize_adcontent($input) {
    return force_balance_tags($input);
  }

  public function sanitize_sponsored_links($input) {
    if (!is_array($input)) {
      return null;
    }

    $sorted = array_values($input);

    foreach ($sorted as &$sponsoredLink) {
      if ($sponsoredLink['thumbnail_attachment_id'] === '') {
        add_settings_error(
          SMARTFORMAT__ADMIN_PAGE,
          SMARTFORMAT__SPONSORED_LINKS,
          __('Thumbnail is required')
        );
      } else {
        $sponsoredLink['thumbnail_attachment_id'] = intval($sponsoredLink['thumbnail_attachment_id']);
      }

      if ($sponsoredLink['title'] === '') {
        add_settings_error(
          SMARTFORMAT__ADMIN_PAGE,
          SMARTFORMAT__SPONSORED_LINKS,
          __('Title is required')
        );
      }

      if ($sponsoredLink['advertiser'] === '') {
        add_settings_error(
          SMARTFORMAT__ADMIN_PAGE,
          SMARTFORMAT__SPONSORED_LINKS,
          __('Advertiser is required')
        );
      }

      if ($sponsoredLink['link'] === '') {
        add_settings_error(
          SMARTFORMAT__ADMIN_PAGE,
          SMARTFORMAT__SPONSORED_LINKS,
          __('Link is required')
        );

        if (filter_var($sponsoredLink['link'], FILTER_VALIDATE_URL) === false) {
          add_settings_error(
            SMARTFORMAT__ADMIN_PAGE,
            SMARTFORMAT__SPONSORED_LINKS,
            __('Link must be URL')
          );
        }
      }
    }

    return $sorted;
  }
}
