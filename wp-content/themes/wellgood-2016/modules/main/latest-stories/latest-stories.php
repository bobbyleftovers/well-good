<?php
global $post;

$args = isset($post->latest_stories_field) ? $post->latest_stories_field : array();

$paged = array_key_exists('paged', $args) ? $args['paged'] : 0;
$footer = array_key_exists('footer', $args) ? $args['footer'] : false;
$exclude_ids = array_key_exists('exclude_ids', $args) ? $args['exclude_ids'] : array();
$ad_data = array_key_exists('ad_data', $args) ? $args['ad_data'] : array();

$display_count = 20;
$section_title = $paged > 0 ? 'More Stories' : 'Latest Stories';
$section_class = $paged > 0 ? 'latest-stories-more' : 'latest-stories-first';

$stories = new WP_Query(array(
  'post_type'  =>  'post',
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'DESC',
  'posts_per_page' => 25,
  'suppress_filters' => true,
  'post__not_in' => $exclude_ids,
)); ?>

<section class="main">
  <section class="latest-stories <?= $section_class ?>">
    <h2 class="module-heading latest-stories__headline"><?= @$section_title; ?></h2>
    <div class="latest-stories__list">
      <?php
      $i = 0;
      while ($stories->have_posts()): $stories->the_post();
        if ($i < $display_count && get_field('hide_from_home') != 1) :
          $exclude_ids[] = $post->ID;
          echo include_partial('post-list-card', array(
            'name' => 'latest-stories'
          ));
          
          if ($ad_data['global_iteration'] % $ad_data['slots_freq'] == 0 && $ad_data['iteration'] < $ad_data['slots_max']) : ?>
            <div class="latest-stories__slot">
              <?php
              the_module('advertisement', array(
                'slots' => array(
                  'slot',
                ),
                'page' => 0,
                'iteration' => $ad_data['iteration']
              )); ?>
            </div>
            <?php
            $ad_data['iteration']++;
          endif;
          $i++;
          $ad_data['global_iteration']++;
        endif;
      endwhile;
      wp_reset_postdata(); ?>
    </div>
    <?php if ($footer) : 
    /* TODO: Create all category archive template
    <div class="latest-stories__footer">
      <a href="<?= '/' ?>" class="btn">Read More Stories</a>
    </div>
    */
    endif; ?>
  </section>
  <section class="sidebar">
    <?php 
    the_module('advertisement', array(
      'slots' => array(
        'rightrail'
      ),
      'page' => 0,
      'iteration' => $paged,
      'sticky' => true
    )); ?>
  </section>
</section>

<?php $post->latest_stories_field = array(
  'exclude_ids' => $exclude_ids,
  'ad_data' => $ad_data
); ?>
