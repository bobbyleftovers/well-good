<?php
$topic_menu = get_nav_menu_locations()['all'];
$topics = wp_get_nav_menu_items($topic_menu);
$overflow = count($topics) % 3;

$email_callout = get_field('all_topics_email_callout', 'options');
$email_callout_headline = get_field('all_topics_email_callout_headline', 'options') ?? 'Become a W+G Insider';
$email_callout_subhead = get_field('all_topics_email_callout_subhead', 'options') ?? 'Sign up for the newsletter to get first access';
?>


<div class="all-topics container">

  <div class="all-topics__header">
    <span class="all-topics__title">All Topics</span>
  </div>

  <div class="all-topics__topics">
    <?php 
    foreach($topics as $i => $topic) :
      $taxonomy = $topic->object;
      $id = $topic->object_id;
      $term = get_term($id, $taxonomy);
      $title = $term->name;
      $link = get_term_link($term->slug, $taxonomy);
      $description = $term->description;
      $thumbnail = get_field('editorialtag_thumbnail', "{$taxonomy}_{$id}");
      $thumbnail_url = $thumbnail ? @$thumbnail['sizes']['medium'] : '';
      $thumbnail_retina_url = $thumbnail ? @$thumbnail['sizes']['article-retina'] : '' 
      ?>

      <a href="<?= $link; ?>" class="all-topics__topic">
        <?php //if ($thumbnail_url) : ?>
          <div class="all-topics__topic-thumbnail">
            <?php the_module('image', array(
              'image_src' => $thumbnail_url, 
              'image_src_retina' => $thumbnail_retina_url,
              'image_alt' => $title,
              'no_pin_class' => true,
              'no_zoom_class' => true
            )); ?>
          </div>
        <?php //endif; ?>
        <div class="all-topics__topic-title">
          <?= $title; ?>
        </div>
        <div class="all-topics__topic-description">
          <?= $description; ?>
        </div>
      </a>
      
      <?php
      if ($email_callout && $i == 4) : ?>
        <div class="all-topics__email">
          <div>
            <div class="all-topics__email-headline">
              <?= $email_callout_headline; ?>
            </div>
            <div class="all-topics__email-subhead">
              <?= $email_callout_subhead; ?>
            </div>
          </div>
        </div>
      <?php 
      endif; ?>

    <?php 
    endforeach; 
    for ($x = 0; $x < $overflow; $x++) echo '<div class="all-topics__blank"></div>'; ?>
  </div>

</div>