<?php

if(is_production() && !is_user_logged_in()) {
  header('Location: '.get_home_url().'/wg-login');
  exit;
}

// Assets bundles
set_theme_template('main-2020');
set_theme_template('styleguide-2020');
set_theme_critical_css('styleguide');

// Header
get_header();

// Page content
brrl_the_module('styleguide-2020/styleguide');

// Footer
get_footer();
