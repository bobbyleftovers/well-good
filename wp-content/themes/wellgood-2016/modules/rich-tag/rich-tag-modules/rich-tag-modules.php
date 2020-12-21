<?php 
global $richtag_ad_index, $richtag_ad_max;
$module_index = 0;
$module_length = sizeof($modules);
$padding_top = $padding_top ?? '';
$margin_top = $margin_top ?? '';
$padding_bottom = $padding_bottom ?? '';
if(!isset($social_share)) $social_share = true;

if ( $modules ) : 
  foreach($modules as $module):
    $module['social_share'] = $social_share;
    $module['class'] = "$padding_top $padding_bottom";
    $module['anchor'] = '<div class="rich-tag-module__anchor top-0 transform -translate-y-header-sm header:-translate-y-header-lg absolute" id="'.sanitize_title($module['title']).'"></div>';
    if( isset($modules[$module_index + 1]) ) $module_next = $modules[$module_index + 1];
    else $module_next = false;
    if( $module_index )$module_prev = $modules[$module_index - 1];
    else $module_prev = false;
    ?>

    <div class="rich-tag-module relative">
      <?php brrl_the_module('rich-tag/rich-tag-'.str_replace("_","-",$module['acf_fc_layout']), $module ); ?>
    </div>

    <div class="rich-tag-module__spacing">
      <?php

      //Add
      if ( $richtag_ad_index < $richtag_ad_max ) :

        $ad_classes = array('rich-tag-module__ad');

        if($module_next && $module_next['acf_fc_layout'] === 'youtube_channel'){
          $ad_classes[] = getSpacing('b', 'p');
        }

        if($module['acf_fc_layout'] === 'youtube_channel'){
          $ad_classes[] = $margin_top;
        }

        the_module( 'advertisement', array(
          'class' => $ad_classes,
          'slots' => array(
            'inline',
            'slot'
          ),
          'page' => 0,
          'iteration' => $richtag_ad_index
        ) ); 
        
        $richtag_ad_index++;
      endif;

      // line separator (posts-list + post-list combination)
      if($module_next 
        && $module['acf_fc_layout'] === 'posts_list' 
        && $module_next['acf_fc_layout'] === 'posts_list'): ?>

        <div class="container rich-tag-module__border">
          <div class="border-t border-tan-medium"></div>
        </div>

      <?php endif;
      ?>
    </div>
  <?php 
  $module_index++;
  endforeach; 
endif;
