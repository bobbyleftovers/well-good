<?php
/* Template Name: Trends 2021 */


function trends_2021_spacing($property = 'mb'){
  return "$property-e35 md:$property-e40 ml:$property-e55 lg:$property-e70";
}

/* NOT purge automated classes:
  mt-e35        mb-e35        pt-e35        pb-e35
  md:mt-e40     md:mb-e40     md:pt-e40     md:pb-e40
  ml:mt-e55     ml:mb-e55     ml:pt-e55     ml:pb-e55
  lg:mt-e70     lg:mb-e70     lg:pt-e70     lg:pb-e70
*/


// Current post
global $post;
global $bg_color;
$bg_color = false;

// Parent
$parent = $post->post_parent ? $post->post_parent : $post->ID;

// Get all children pages colors
$children = new WP_Query( array(
  'post_type'      => 'page',
  'posts_per_page' => -1,
  'post_parent'    => $parent,
  'order'          => 'ASC',
  'orderby'        => 'menu_order'
) );


$colors = array();
foreach($children->posts as $child){
  $colors[$child->ID] = array(
    'primary' => get_field('color_primary', $child->ID) ?: '#f39588',
    'secondary' => get_field('color_secondary', $child->ID) ?: '#fbe0d7',
    'accent-1' => get_field('color_accent_1', $child->ID) ?: '#fde4ff',
    'accent-2' => get_field('color_accent_2', $child->ID) ?: '#fff1fc',
    'accent-3' => get_field('color_accent_3', $child->ID) ?: '#ffe9c8',
    'body-background' => get_field('color_body_background', $child->ID) ?: '#fff5f1',
    'trend-1' => get_field('color_trend_spotlight_1', $child->ID),
    'trend-2' => get_field('color_trend_spotlight_2', $child->ID),
    'trend-3' => get_field('color_trend_spotlight_3', $child->ID),
    'trend-4' => get_field('color_trend_spotlight_4', $child->ID)
  );
}

// Data passed to all components
$data = array(
  'colors' => $colors,
  'is_parent' => !$post->post_parent,
  'parent' => $parent,
  'post' => $post,
  'children' => $children
);

// Gutenberg filters
// Change block modules (only frontend)
add_filter('brrl_render_block_name', function($name){
  switch($name):
    case 'acf/slideshow':
      return 'trends-2021/trends-2021-slideshow';
    case 'acf/trend-spotlight':
      return 'trends-2021/trends-2021-spotlight';
    case 'acf/trends-video':
      return 'trends-2021/trends-2021-video';
    case 'acf/related-posts':
      return 'trends-2021/trends-2021-related-posts';
    case 'acf/child-posts':
      return 'trends-2021/trends-2021-home-grid';
    case 'acf/trends-2021-event':
      return 'trends-2021/trends-2021-event';
    case 'core/image':
      return 'trends-2021/trends-2021-base-image';
    case 'acf/trends-past-decade':
      return 'trends-2021/trends-2021-past-decade';
    case 'acf/sponsors':
      return 'trends-2021/trends-2021-sponsors';
    case 'acf/advertisement':
      return 'trends-2021/trends-2021-advertisement';
  endswitch;
  return $name;
});

// Class filters for core blocks
add_filter( 'core/paragraph:class', function($class, $block) {
  if(isset($block['next']) && $block['next']['blockName'] == 'acf/video') $mb = 'mb-e45';
  else $mb = 'mb-e22 md:mb-e33';
  if (isset($block['prev']) && isset($block['prev']['blockName'])) $class = "$class prev-".$block['prev']['blockName'];
  return "$class text-big $mb last:mb-e0";
}, 10, 2);

add_filter( 'core/quote:class:text', function($class, $block) {
  return 'text-quote text-center';
}, 10, 2);

add_filter('core/heading:tag', function($tag){
  if($tag != 'h2' && $tag != 'h4') return 'h3';
  return $tag;
}, 2);

add_filter( 'core/heading:class', function($class, $block) {
  $class = "$class xs:text-center mt-e40 first:mt-e0 last:mb-e0";
  if(isset($block['next']) && $block['next']['blockName'] === 'acf/slideshow') $class = "$class text-center";
  else $class = "$class text-left";
  if($block['tag'] == 'h2') return "$class mb-e10";
  return "$class mb-e14 md:mb-e10 ";
}, 10, 2);

add_filter('wg/button:align', function($align){
  return 'center';
});

add_filter('wg/button:args', function($args){
  if($args['type'] == 'white') $args['text_class'] = 'text-gray';
  return $args;
});


// Trends 2021 home
if($data['is_parent']):

  // Critical CSS
  set_theme_critical_css('trends-2021-home');

  // Gutenberg blocks
  $blocks = brrl_get_blocks();
  if(isset($blocks[0]) && $blocks[0]['blockName'] == 'core/paragraph'){
    $data['hero_content'] = $blocks[0]['innerHTML'];
    unset($blocks[0]);
    $blocks = array_values($blocks);
  }

  $data['content'] = brrl_get_the_content(null, $data, $blocks);

  // Layout
  $data['render'] = function() use ($data) {
    brrl_the_module('trends-2021/trends-2021-home-hero', $data);
    brrl_the_module('trends-2021/trends-2021-home-content', $data);
  };

  //Render
  brrl_the_module('trends-2021/trends-2021-layout', $data);


//Trends 2021 children
else:

  // hero content
  $data['hero_content'] = get_field('hero_content');
  
  //get lightness
  $hero_color_lightness = $data['hero_content']['hero_lightness_overwrite'] ?: 'default';
  if($hero_color_lightness === 'default') $hero_color_lightness = getColorLightness($colors[$post->ID]['secondary']);
  
  //get color
  if ($hero_color_lightness === 'dark') {
    $data['hero_color'] = 'text-white';
  } else {
    $data['hero_color'] = 'text-gray-dark';
  }

  // Critical CSS
  set_theme_critical_css('trends-2021-child'); 

  // Get last block
  global $last_block;
  add_filter('brrl_render_block', function($block) {
    global $last_block;
    if($block['is_last']) $last_block = $block['blockName'];
    return $block;
  });

  // Gutenberg content
  $data['blocks'] = brrl_get_blocks();
  $data['menu'] = array();
  if($data['blocks']){
    foreach($data['blocks'] as &$block){
      if($block['blockName'] == 'acf/trend-spotlight'){
        $label = $block['attrs']['data']['subnav_label'] ?? $block['attrs']['data']['trend_title'];
        $slug = sanitize_title($label);
        $block['slug'] = $slug;
        $data['menu'][] = array(
          'label' => $label,
          'slug' => $slug
        );
      }
    }
  }
  $data['content'] =  brrl_get_the_content(null, $data, $data['blocks']);
  $data['last_block'] = $last_block;

  // Layout
  $data['render'] = function() use ($data) {
    brrl_the_module('trends-2021/trends-2021-child-hero', $data);
    brrl_the_module('trends-2021/trends-2021-child-subnav', $data);
    brrl_the_module('trends-2021/trends-2021-content', $data);
    brrl_the_module('trends-2021/trends-2021-more-posts', $data);
  };

  //Render
  brrl_the_module('trends-2021/trends-2021-layout', $data);
endif;
?>
