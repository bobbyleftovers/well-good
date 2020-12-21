<?php
/* Template Name: Home Page */

get_header();

global $post;

$ad_slots_freq = 3;
$ad_slots_max = 5;
$ad_iteration = 0;
$ad_data = array(
  'slots_freq' => $ad_slots_freq,
  'slots_max' => $ad_slots_max,
  'iteration' => $ad_iteration,
  'global_iteration' => 0,
  'background' => 'white'
); ?>

<article class="home wg__inline-ad-wrapper" data-module-init="home-page">
  <div class="container">
    <?php
    the_module('relationship-stories', 'featured_stories', '', true);
    $exclude_ids = isset($post->relationship_stories_sub_sub_field) ? $post->relationship_stories_sub_sub_field : array();

    the_module('latest-stories', array(
      'paged' => 0,
      'footer' => false,
      'exclude_ids' => $exclude_ids,
      'ad_data' => $ad_data
    ));
    $ad_data = isset($post->latest_stories_field) && array_key_exists('ad_data', $post->latest_stories_field) ? $post->latest_stories_field['ad_data'] : $ad_data;
    $exclude_ids = isset($post->latest_stories_field) && array_key_exists('exclude_ids', $post->latest_stories_field) ? $post->latest_stories_field['exclude_ids'] : $exclude_ids; ?>
  </div>
	<?php

	if (have_rows('modules')):
    $i = 0;
    while (have_rows('modules')) : the_row();
      if ($i == 2) :
        the_module('advertisement', array(
          'slots' => array(
            'horizontal'
          ),
          'page' => 0,
          'iteration' => 1
        ));
      endif;
      switch (get_row_layout()) :
        case 'collection' :
          the_module('collection', 'collection');
          break;

        case 'wellness_experts':
          $ad_data['background'] = 'grey';
          the_module('wellness-experts');
          break;

        case 'alternate_spotlight':
          the_module('alternate-spotlight');
          break;

        case 'series_spotlight':
          $ad_data['background'] = 'white';
          the_module('series-spotlight');
          break;

        case 'campaign_spotlight':
          the_module('campaign-spotlight');
          break;

        case 'instagram_feed':
          echo '<div class="container alternate mb-e50">';
          the_module('instagram-feed');
          echo '</div>';
          break;

        case 'most_popular':
            brrl_the_module('trending-articles', array(
              'fetch_parsely' => true,
              'title' => get_sub_field('trending_articles_title')
            ));
            break;

      endswitch;

      if ($ad_data['global_iteration'] % $ad_data['slots_freq'] == 0 && $ad_data['iteration'] < $ad_data['slots_max']) :
        $slot_classes = array('home__slot');
        if ($ad_data['iteration'] == ($ad_data['slots_max'] - 1)) :
          array_push($slot_classes, 'home__slot--last');
        endif; ?>
        <div class="<?= implode(' ', $slot_classes); ?>">
          <?php
          the_module('advertisement', array(
            'class' => $ad_data['background'] == 'grey' ? array('container--grey') : array(),
            'slots' => array(
              'slot',
            ),
            'page' => 0,
            'iteration' => $ad_data['iteration']
          ));
          $ad_data['iteration']++; ?>
        </div>
      <?php
      endif;
      $i++;
      $ad_data['global_iteration']++;
    endwhile;
  endif; ?>
</article>

<?php get_footer(); ?>
