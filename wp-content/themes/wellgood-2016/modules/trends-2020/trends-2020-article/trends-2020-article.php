<?php

$is_lookback = isset($module['is_lookback']) && $module['is_lookback'];

$module['image_size'] = (isset($module['image_size']) && $module['image_size']) ? $module['image_size'] : 'medium_large';

$module['post']->post_title = $module['overwrite']['title'] ?: $module['post']->post_title;
$excerpt_lenght = $is_lookback ? 15: 60;
$module['post']->post_excerpt = $module['overwrite']['description'] ?: shorten_string_words_count(strip_tags($module['post']->post_content), $excerpt_lenght);

if($module['overwrite']['image']){
  $module['post']->thumbnail = $module['overwrite']['image']['sizes'][$module['image_size']];
  $module['post']->thumbnail_caption = $module['overwrite']['image']['caption'];
} else {
  $module['post']->thumbnail = get_the_post_thumbnail_url($module['post'], $module['image_size']);
  $module['post']->thumbnail_caption = get_the_post_thumbnail_caption($module['post']);
}
if(!$is_lookback): 

  include_module('trends-2020/trends-2020-article-2020', $module);

else:

  include_module('trends-2020/trends-2020-article-lookback', $module);

endif;

?>