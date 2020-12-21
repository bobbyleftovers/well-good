<?php

class SmartFormatFeed {
  public function __construct() {
    add_action('init', array($this, 'init'));
  }

  public function init() {
    add_feed('smartformat', array($this, 'render'));
    add_filter('feed_content_type', array($this, 'content_type'), 10, 2);
    add_filter('the_post', 'reset_sponsored_link');
  }

  public function render() {
    load_template(SMARTFORMAT__PLUGIN_DIR . '/feed.php');
  }
  
  public function content_type($content_type, $type) {
    if ('smartformat' === $type) {
      return feed_content_type('rss2');
    }

    return $content_type;
  }
}

function reset_sponsored_link() {
  global $sponsored_links;
  global $sponsored_links_index;

  $sponsored_links = get_option(SMARTFORMAT__SPONSORED_LINKS);
  if (is_array($sponsored_links)) {
    foreach ($sponsored_links as &$sponsored_link) {
      $thumbnail_attachment_url = wp_get_attachment_url($sponsored_link['thumbnail_attachment_id']);
      if ($thumbnail_attachment_url !== false) {
        $sponsored_link['thumbnail'] = $thumbnail_attachment_url;
      } else {
        $sponsored_link['thumbnail'] = '';
      }
    }
  } else {
    $sponsored_links = array();
  }

  $sponsored_links_index = 0;
}

function the_smartformat_ttl($default_ttl = 15) {
  $ttl = get_option(SMARTFORMAT__TTL);
  echo $ttl ? $ttl : $default_ttl;
}

function have_smartformat_sponsored_links() {
  global $sponsored_links;
  global $sponsored_links_index;

  if ($sponsored_links == null) {
    reset_sponsored_link();
  }

  return count($sponsored_links) > $sponsored_links_index;
}

function get_smartformat_sponsored_link() {
  global $sponsored_links;
  global $sponsored_links_index;

  return $sponsored_links[$sponsored_links_index++];
}

function has_smartformat_adcontent() {
  $adcontent = get_option(SMARTFORMAT__ADCONTENT);
  return !empty($adcontent);
}

function the_smartformat_adcontent() {
  echo get_option(SMARTFORMAT__ADCONTENT);
}

function has_smartformat_analytics() {
  $analytics = get_option(SMARTFORMAT__ANALYTICS);
  return !empty($analytics);
}

function the_smartformat_analytics() {
  echo get_option(SMARTFORMAT__ANALYTICS);
}
