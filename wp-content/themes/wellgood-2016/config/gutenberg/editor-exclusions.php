<?php

/**
  *
  *  Remove these page templates from editing with Gutenberg
  *
  *  @var array
  */
  $excluded_page_templates = array(
    'templates/page-landing.php'
  );

  /**
  *
  *  Only these page templates can be edited on Gutenberg
  *
  *  @var array
  */
  $include_only_page_templates = array(
    'templates/page-trends-2021.php',
    'templates/page-changemakers.php',
    'templates/page-renew-year-2021.php'
  );

  /**
  *
  *  Only these posts types can be edited on Gutenberg
  *
  *  @var array
  */
  $include_only_post_types = array(
    'page'
  );

  return array(
    'include_only_page_templates' => $include_only_page_templates,
    'excluded_page_templates' => $excluded_page_templates,
    'include_only_post_types' => $include_only_post_types
  );