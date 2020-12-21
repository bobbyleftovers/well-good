<?php

/*
*   Common blocks
*   -------------
*/

# acf/video
# acf/related-posts
# acf/slideshow

return array(
  /**
  *
  *  Oembed video block
  *
  *  @var array
  */
  array(
    'name'              => 'video',
    'title'             => 'Video',
    'description'       => 'Oembed Video',
    'category'          => 'common',
    'icon'              => 'video-alt3',
    'mode'              => 'edit',
    'align'             => false,
    'align_text'        => false,
    'keywords'          => array( 'video', 'youtube', 'vimeo', 'embed', 'iframe' ),
    'post_types'        => array('page', 'post')
  ),

  /**
  *
  *  Related stories
  *
  *  @var array
  */
  array(
    'name'              => 'related-posts',
    'title'             => 'Related posts',
    'description'       => 'Related Stories',
    'category'          => 'common',
    'icon'              => 'feedback',
    'mode'              => 'preview',
    'align'             => false,
    'align_text'        => false,
    'keywords'          => array( 'posts', 'related', 'content', 'parse.ly', 'stories' ),
    'post_types'        => array('page', 'post'),
    'supports'          => array(
      'align' => false,
      'mode' => false,
      'jsx' => true
    )
  ),

  /**
  *
  *  Post Card
  *
  *  @var array
  */
  array(
    'name'              => 'post-card',
    'title'             => 'Post Card',
    'description'       => 'Single post selector',
    'category'          => 'common',
    'icon'              => 'admin-post',
    'mode'              => 'preview',
    'align'             => false,
    'align_text'        => false,
    'keywords'          => array( 'posts', 'related', 'content', 'parse.ly', 'stories' ),
    'post_types'        => array('page', 'post'),
    'supports'          => array(
      'align' => false,
      'mode' => true,
      'jsx' => true
    )
  ),

  /**
  *
  *  Slideshow
  *
  *  @var array
  */
  array(
      'name'              => 'slideshow',
      'title'             => 'Slideshow',
      'description'       => 'Slideshow',
      'category'          => 'common',
      'icon'              => 'images-alt',
      'mode'              => 'edit',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'list', 'ul', 'ol' ),
      'post_types'        => array('page', 'post'),
      'supports'          => array(
        'mode' => false,
        'align' => false,
      )
  ),

    /**
    *
    *  Show child posts
    *
    *  @var array
    */
    array(
      'name'              => 'child-posts',
      'title'             => 'Child Posts',
      'description'       => 'Display child posts on a parent post',
      'category'          => 'common',
      'icon'              => 'schedule',
      'mode'              => 'edit',
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => false,
        'multiple' => false
      ),
      'post_types'        => array('page', 'post')
    ),

    /**
    *
    *  Advertisement inline slot
    *
    *  @var array
    */
    array(
      'name'              => 'advertisement',
      'title'             => 'Advertisement',
      'description'       => 'Display Ad Slot (max 5)',
      'category'          => 'common',
      'icon'              => 'align-wide',
      'mode'              => 'preview',
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => false
      ),
      'post_types'        => array('page', 'post')
    ),

     /**
    *
    *
    *  @var array
    */
    array(
      'name'              => 'sponsors',
      'title'             => "Sponsors logos gallery",
      'description'       => "",
      'category'          => 'common',
      'icon'              => 'schedule',
      'mode'              => 'edit',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'logos', 'brand', 'sponsor', 'partner'),
      'post_types'        => array('page'),
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => false,
        'multiple' => true
      )
      ),


      /**
      *
      *  Signup Form
      *
      *  @var array
      */
      array(
        'name'              => 'signup-form',
        'title'             => 'Newsletter signup form',
        'description'       => 'Custom widget for a signup form',
        'category'          => 'common',
        'icon'              => 'email',
        'mode'              => 'preview',
        'supports'          => array(
          'align' => false,
          'mode' => false,
          'jsx' => true
        )
      ),


);