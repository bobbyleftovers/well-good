<?php

/*
*   Layout blocks
*   -------------
*/

# acf/text-and-image
# acf/wrapper

return array(
    /**
    *
    *  Text + Image
    *
    *  @var array
    */
    array(
      'name'              => 'text-and-image',
      'title'             => "Text & Image",
      'description'       => "Title, description and image",
      'category'          => 'layout',
      'icon'              => 'id',
      'mode'              => 'preview',
      'align'             => false,
      'align_text'        => false,
      'keywords'          => array( 'title', 'image', 'text', 'changemakers' ),
      'post_types'        => array('page', 'post'),
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => true
      )
    ),

    /**
    *
    *  Simple HTML Wrapper with InnerBlocks
    *
    *  @var array
    */
    array(
      'name'              => 'wrapper',
      'title'             => 'Wrapper',
      'description'       => 'Html wrapper',
      'category'          => 'layout',
      'icon'              => 'html',
      'mode'              => 'preview',
      'supports'          => array(
        'align' => false,
        'mode' => false,
        'jsx' => true
      ),
      'post_types'        => array('page', 'post')
    )
);