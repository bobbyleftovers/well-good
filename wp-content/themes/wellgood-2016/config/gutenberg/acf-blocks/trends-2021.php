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
    *  Trends 2021 video module
    *  Oembed video + Title + Text
    *
    *  @var array
    */
    array(
      'name'              => 'trends-video',
      'title'             => 'Trends Video',
      'description'       => 'Trends 2021 video module with title and description',
      'category'          => 'common',
      'icon'              => 'format-video',
      'mode'              => 'preview',
      'align'             => 'full',
      'align_text'        => false,
      'keywords'          => array( 'video', 'youtube', 'vimeo', 'embed', 'iframe', 'trends', '2021' ),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => array( 'full' ),
        'mode' => false,
        'jsx' => true
      )
    ),

    /**
    *
    *  Expert's take snippet
    *
    *  @var array
    */
    array(
      'name'              => 'expert-take',
      'title'             => "Expert's take",
      'description'       => "Trends 2021 expert's take module",
      'category'          => 'layout',
      'icon'              => 'id',
      'mode'              => 'preview',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'expert', 'person', 'profile' ),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => true
      )
    ),

    /**
    *
    *  Trends 2021 Spotlight
    *
    *  @var array
    */
    array(
      'name'              => 'trend-spotlight',
      'title'             => 'Trend spotlight',
      'description'       => 'Trend spotlight',
      'category'          => 'layout',
      'icon'              => 'welcome-view-site',
      'mode'              => 'preview',
      'align'             => 'full',
      'supports'          => array(
        'align' => array( 'full' ),
        'mode' => false,
        'jsx' => true
      ),
      'post_types'        => array('page')
    ),

    /**
    *
    *  Trends 2021 Outlined text
    *
    *  @var array
    */
    array(
      'name'              => 'stroke-text',
      'title'             => 'Stroke text',
      'description'       => 'Big featured outlined text',
      'category'          => 'common',
      'icon'              => 'editor-textcolor',
      'mode'              => 'preview',
      'align'             => 'left',
      'supports'          => array(
        'align' => array( 'left' ),
        'mode' => false,
        'jsx' => true
      ),
      'post_types'        => array('page')
    ),

    /**
    *
    *  Event block for Trends 2021 homepage
    *
    *  @var array
    */
    array(
      'name'              => 'trends-2021-event',
      'title'             => "Trends 2021 Event",
      'description'       => "Event block for Trends 2021 homepage",
      'category'          => 'layout',
      'icon'              => 'id',
      'mode'              => 'preview',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'title', 'image', 'text', 'event' ),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => true,
        'multiple' => false
      )
    ),


    /**
    *
    *
    *  @var array
    */
    array(
      'name'              => 'trends-past-decade',
      'title'             => "Trends From Past Decade",
      'description'       => "Slideshow > Page selector block for Trends 2021 homepage",
      'category'          => 'layout',
      'icon'              => 'table-row-before',
      'mode'              => 'edit',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( ),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => false,
        'multiple' => false
      )
    )
);