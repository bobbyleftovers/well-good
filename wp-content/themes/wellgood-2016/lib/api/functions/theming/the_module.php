<?php


// Render a module from the "modules" directory, optionally pass an alternate field name
function the_module($module_name = '', $field_name = '', $sub_field_name = '', $sub_sub_field_name = '', $sub_sub_sub_field_name = '', $sub_sub_sub_sub_field_name = '', $sub_sub_sub_sub_sub_field_name = '') {
  if(empty($module_name)) {
    return false;
  }

  $module_slug = get_module_slug($module_name);

  // Pass field name to post object as $post->module_name_field
  if(!empty($field_name)) {
    global $post;
    if(!$post) $post = new stdClass();
    $module_field = dashes_to_underscores( $module_slug ).'_field';
    $post->{$module_field} = $field_name;

    // Pass sub field name to post object as $post->module_name_sub_field
    if(!empty($sub_field_name)) {
      $module_field = dashes_to_underscores( $module_slug ).'_sub_field';
      $post->{$module_field} = $sub_field_name;

    }

    // Pass next sub field to post object as $post->module_name_sub_sub_field
    if(!empty($sub_sub_field_name) && $sub_sub_field_name !== '') {
      $module_field = dashes_to_underscores( $module_slug ).'_sub_sub_field';
      $post->{$module_field} = $sub_sub_field_name;
    }

    // Pass next sub field to post object as $post->module_name_sub_sub_sub_field
    if(!empty($sub_sub_sub_field_name) && $sub_sub_sub_field_name !== '') {
      $module_field = dashes_to_underscores( $module_slug ).'_sub_sub_sub_field';
      $post->{$module_field} = $sub_sub_sub_field_name;
    }

    // Pass next sub field to post object as $post->module_name_sub_sub_sub_field
    if(!empty($sub_sub_sub_sub_field_name) && $sub_sub_sub_sub_field_name !== '') {
      $module_field = dashes_to_underscores( $module_slug ).'_sub_sub_sub_sub_field';
      $post->{$module_field} = $sub_sub_sub_sub_field_name;
    }

    // Pass next sub field to post object as $post->module_name_sub_sub_sub_field
    if(!empty($sub_sub_sub_sub_sub_field_name) && $sub_sub_sub_sub_sub_field_name !== '') {
      $module_field = dashes_to_underscores( $module_slug ).'_sub_sub_sub_sub_sub_field';
      $post->{$module_field} = $sub_sub_sub_sub_sub_field_name;
    }
  }

  do_action($module_name.'_before');

  // Use "locate_template()" instead of "get_template_part()" to retain scope
  locate_template( get_module_path($module_name), true, false );

  do_action($module_name.'_after');
}