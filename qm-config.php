<?php

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (strpos($actual_link, 'wellandgood.com') !== false && strpos($actual_link, 'wellandgood.com/wp-admin') == false) {
  define('QM_DISABLED', true); //disable front on wellandgood.com
} else if(strpos($actual_link, '/wp-admin') == false){
  define('QM_DISABLED', true); //disable all front while jquery issue is not fixed
}

define('QM_DB_EXPENSIVE', 0.01);
define('QM_ENABLE_CAPS_PANEL', true);
define('QM_HIDE_CORE_ACTIONS', true);
define('QM_NO_JQUERY', true);