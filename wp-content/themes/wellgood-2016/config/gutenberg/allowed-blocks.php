<?php

/**
  *
  *  Allowed only blocks
  *
  *  @var array
  */
  $allowed_blocks = array();

  /**
  *
  *  Allowed blocks by post type
  *
  *  @var array
  */
  $allowed_blocks_by_post_type = array(
    'post' => array(),
    'page' => array()
  );

  /**
  *
  *  Allowed blocks by page template
  *
  *  @var array
  */
  $allowed_blocks_by_page_template = array(
    'templates/page-trends-2021.php:child' => array(
      'core/paragraph',
      'core/heading',
      'core/block',
      'core/quote',
      'core/image',
      'acf/slideshow',
      'acf/trend-spotlight',
      'acf/stroke-text',
      'acf/video',
      'acf/trends-video',
      'acf/expert-take',
      'acf/related-posts',
      'acf/advertisement',
      'acf/signup-form',
      'wg/button'
    ),
    'templates/page-trends-2021.php:parent' => array(
      'acf/child-posts',
      'core/paragraph',
      'acf/advertisement',
      'acf/trends-past-decade',
      'acf/sponsors'
    ),
    'templates/page-renew-year-2021.php:parent' => array(
      'core/paragraph',
      'acf/post-card',
      'acf/slideshow'
    ),
    'templates/page-renew-year-2021.php:child' => array(
      'core/paragraph',
      'acf/renew-year-2021-posts'
    ),
    'templates/page-changemakers.php' => array(
      'core/paragraph',
      'acf/text-and-image'
    )
  );

  return array(
    'allowed_blocks' => $allowed_blocks,
    'allowed_blocks_by_post_type' => $allowed_blocks_by_post_type,
    'allowed_blocks_by_page_template' => $allowed_blocks_by_page_template
  );