<?php

$category = get_queried_object();
$is_rich_tag = get_field('is_rich_page',  $category);

if($is_rich_tag) $template = 'editorial-rich-tag';
else $template = 'editorial-tag';

if($template === 'editorial-rich-tag') {
  set_theme_template('main-2020');
  set_theme_template('rich-tag');
  set_theme_critical_css('rich-tag');
}

get_header();

get_template_part( 'templates/'.$template );

get_footer();
?>
