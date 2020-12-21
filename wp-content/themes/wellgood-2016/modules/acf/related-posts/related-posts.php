<?php

if($is_editor):
  $allowed_blocks = array( 'wg/plain-text' );
  $template = array(
      array( 'wg/plain-text', array(
          'placeholder' => "Related Stories"
      ))
  );
  $title = '<div class="pt-e20 plain-text-inherit"><InnerBlocks 
          allowedBlocks="'.esc_attr( wp_json_encode( $allowed_blocks ) ) .'"
          template="'.esc_attr( wp_json_encode( $template ) ).'" 
          templateLock="all"/></div>';
else: 
  $title = trim(strip_tags($innerBlocks[0]['innerHTML']));
  if($title == '') $title = 'Related Stories';
endif;

if(!isset($posts) || !$posts || sizeof($posts) == 0) $posts = array(array(), array());
?>

<div class="relative related-posts text-left">
  <?php brrl_the_module('main/related-content', array(
    "posts" => $posts,
    "gutenberg" => true,
    "title" => $title,
    "is_editor" => $is_editor,
    'title_class' => 'text-inherit'
  )); ?>
</div>