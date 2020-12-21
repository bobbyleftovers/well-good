<?php

// Vars
$data = get_queried_object();
$data->menu = get_categories(array('hierarchical' => false, 'child_of' => $data->term_id));
$data->intro = get_field('intro', $data);
$data->hero_image = get_field('hero_image', $data);
$data->modules = get_field('modules', $data);

// Prepare new main query
$query = array();
$query['posts_per_page'] = 72;
$query['post__not_in'] = array();

// Page modules data adjustments
if ($data->modules) :
  foreach($data->modules as $key => $module){
    if($module['acf_fc_layout'] === 'posts_list'){

      $length = sizeof($module['posts_by_term_and_keyword']);
      $length = $module['newsletter_card']['is_active'] ? $length+1 : $length;

      foreach($module['posts_by_term_and_keyword'] as $queried_post){
        $query['post__not_in'][] = $queried_post->ID;
      }

    } else {
      if($module['posts'] && is_array($module['posts'])){
        foreach($module['posts'] as $queried_post){
          $query['post__not_in'][] = $queried_post->ID;
        }
      }
    }
  }
endif;

//Ads
global $richtag_ad_index, $richtag_ad_max;
$richtag_ad_index = 0;
$richtag_ad_max = 5;

// Reset main query
global $wp_query;
$wp_query = new WP_Query( array_merge((array) $wp_query->query,(array) $query) );

// Spacing
//Spacing classes
function getSpacing($pos, $prop = 'p'){
  if($pos !== 'bottom') {
    if($pos == 'top') $pos = 't';
    $prop = $prop.$pos;
    return "$prop-e40 ml:$prop-e55 lg:$prop-e75";
  }
  else {
    $prop = $prop.='b';
    return "$prop-e5 ml:$prop-e20 lg:$prop-e40";
  }
}
$data->padding_top = getSpacing('top', 'p');
$data->margin_top  = getSpacing('top', 'm');
$data->padding_bottom = getSpacing('bottom', 'p');
$data->social_share = true;

?>

<div class="wg__inline-ad-wrapper">
  <?php
  // Inject components
  brrl_the_module('rich-tag/rich-tag-hero', $data);
  brrl_the_module('rich-tag/rich-tag-menu', $data);
  brrl_the_module('rich-tag/rich-tag-intro', $data);
  brrl_the_module('rich-tag/rich-tag-modules', $data);
  brrl_the_module('main-2020/posts-grid', array(
    'title' => 'All Articles',
    'class' => $data->padding_top,
    'social_share' => $data->social_share,
    'inline_ads' => array(
      'index' => $richtag_ad_index,
      'max' => $richtag_ad_max
    ),
  )); ?>
</div>


<?php

  /* Purge CSS: 
  /* pt-e40 ml:pt-e55 lg:pt-e75
  /* pb-e40 ml:pb-e55 lg:pb-e75
  /* mt-e40 ml:mt-e55 lg:mt-e75
  /* mb-e40 ml:mb-e55 lg:mb-e75
  /* pt-e5 ml:pt-e20 lg:pt-e40
  /* pb-e5 ml:pb-e20 lg:pb-e40
  /* mt-e5 ml:mt-e20 lg:mt-e40
  /* mb-e5 ml:mb-e20 lg:mb-e40
  */


?>