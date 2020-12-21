<?php

use \WG\Settings\Scripts;

function set_theme_template($template){
  global $WG_THEME_SCRIPTS;
  $WG_THEME_SCRIPTS->add_theme_template($template);
}

function set_theme_critical_css($template){
  global $WG_THEME_SCRIPTS;
  $WG_THEME_SCRIPTS->set_theme_critical_css($template);
}

function add_theme_js($template){
  global $WG_THEME_SCRIPTS;
  $WG_THEME_SCRIPTS->add_theme_js($template);
}

function add_theme_css($template){
  global $WG_THEME_SCRIPTS;
  $WG_THEME_SCRIPTS->add_theme_css($template);
}

function theme_enqueue_bundle($name, $js_dependencies = array(), $css_dependencies = array()){
  $scripts = new Scripts(false);
  $scripts->enqueue_bundle(
    $name,
    $js_dependencies,
    $css_dependencies
  );
}

