<?php

require_once(SMARTFORMAT__PLUGIN_DIR . '/class.smartformat-admin.php');
require_once(SMARTFORMAT__PLUGIN_DIR . '/class.smartformat-feed.php');

class SmartFormat {
  private static $instance;

  private $admin;
  private $feed;

  public static function init() {
    if (!self::$instance) {
      self::$instance = new SmartFormat();
    }

    return self::$instance;
  }

  private function __construct() {
    $this->admin = new SmartFormatAdmin();
    $this->feed = new SmartFormatFeed();
  }
}
