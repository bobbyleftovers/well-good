<?php
/* Template Name: Renew Year 2021 */

// Assets bundles
set_theme_template('main-2020');
set_theme_template('renew-year-2021');

// Current post
global $post;

// Parent
$parent = $post->post_parent ? $post->post_parent : $post->ID;

// Get all children
$children = new WP_Query( array(
  'post_type'      => 'page',
  'posts_per_page' => -1,
  'post_parent'    => $parent,
  'order'          => 'ASC',
  'orderby'        => 'menu_order'
) );

//Bg colors
$bg_colors = array();
$bg_colors[$post->ID] = get_field('color_bg', $post->ID);
foreach($children->posts as $child){
  $bg_colors[$child->ID] = get_field('color_bg', $child->ID) ?: '#175762';
}

// Data passed to all components
$data = array(
  'bg_colors' => $bg_colors,
  'is_parent' => !$post->post_parent,
  'parent' => $parent,
  'post' => $post,
  'children' => $children->posts
);

// Gutenberg filters
// Change block modules (only frontend)
add_filter('brrl_render_block_name', function($name){
  switch($name):
    case 'acf/slideshow':
      return 'renew-year-2021/renew-year-2021-slideshow';
    case 'acf/post-card':
      return 'renew-year-2021/renew-year-2021-home-post-card';
    case 'acf/renew-year-2021-posts':
      return 'renew-year-2021/renew-year-2021-posts';
  endswitch;
  return $name;
});

// Gutenberg blocks
$blocks = brrl_get_blocks();
if(isset($blocks[0]) && $blocks[0]['blockName'] == 'core/paragraph'){
    $data['hero_content'] = strip_tags($blocks[0]['innerHTML'], '<i><strong><b><a><em>');
    unset($blocks[0]);
    $blocks = array_values($blocks);
}

// Content
$data['content'] = brrl_get_the_content(null, $data, $blocks);


// Trends 2021 home
if($data['is_parent']):

  // Critical CSS
  set_theme_critical_css('renew-year-2021-home');

  // Moduldes
  $data['modules'] = array(
    'home-hero',
    'content'
  );

//Children
else:

  // Critical CSS
  set_theme_critical_css('renew-year-2021-child'); 

  // Modules
  $data['modules'] = array(
    'child-hero',
    'child-menu',
    'content'
  );

endif;


//Render
brrl_the_module('renew-year-2021/renew-year-2021-layout', $data);
?>
