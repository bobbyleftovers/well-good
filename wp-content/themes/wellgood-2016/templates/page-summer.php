<?php
// Template Name: 99 Days of Summer
get_header();
$parent_id = get_the_parent_id() == 0 ? get_the_ID() :  get_the_parent_id();
$children_args = array(
  'post_parent' => $parent_id,
  'post_type' => 'page'
);
$is_summer_v2 = get_field('is_v2') === true;
$nav_container_classes = 'border-t-05 border-b-05 border-solid border-seafoam-dark py-e15 ml:py-e50';
$verticals = get_children($children_args);

if(empty($verticals)) {
  $nav_container_classes = '';
}
?>
<h1 class="visually-hidden"><?= get_the_title() ?></h1>
<section class="summer-posts-container">
  <nav class="summer-subnav-container <?= $is_summer_v2 ? $nav_container_classes : '' ?>">
    <?php

      the_module('summer/summer-subnav', $verticals);
    ?>
  </nav>
  <div id="loading-state" class="summer-loading hidden text-center text-default text-seafoam-dark">Loading Posts...</div>
  <div class="articles-container"></div>
</section>

</div> <!-- .summer-wrapper -->

<?php
get_footer();
?>
