<?php

namespace WG\Services;

class Pantheon {

  function __construct(){
    add_filter('robots_txt', array($this,'custom_robots_txt'), 10,  2);
  }

    /**
   * Pantheon typically manages their robots.txt files, so we need to add a specific
   * workaround if we want custom values in our file.
   * @see https://pantheon.io/docs/bots-and-indexing/
   */
  function custom_robots_txt($output, $public) {
    $robots_txt = "User-agent: * \n";
    $robots_txt .= "Disallow: /wp-admin/ \n";
    $robots_txt .= "Allow: /wp-admin/admin-ajax.php \n";
    $robots_txt .= "User-agent: Twitterbot \n";
    $robots_txt .= "Disallow: \n";
    $robots_txt .= "User-agent: rogerbot \n";
    $robots_txt .= "Disallow: / \n";

    return $robots_txt;
  }
}