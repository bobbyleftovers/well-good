<?php

/*
*   Trends 2021 blocks
*   ------------------
*/

# acf/trends-video
# acf/expert-take
# acf/trend-spotlight
# acf/stroke-text

return array(
    /**
    *
    *  Renew year 2021 posts' module
    *
    *  @var array
    */
    array(
      'name'              => 'renew-year-2021-posts',
      'title'             => "Posts module",
      'description'       => "Renew year 2021 posts' module",
      'category'          => 'layout',
      'icon'              => 'welcome-widgets-menus',
      'mode'              => 'preview',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'posts', 'scroll', 'layout' ),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => false,
        'mode' => true,
        'jsx' => true
      )
    )

);