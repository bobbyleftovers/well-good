<?php 
$length = sizeof($children->posts)-1; 
if($length < 1) return;

?>
<div class="trends-2021-more-posts bg-body-background--<?=$post->ID?> <?= trends_2021_spacing('pt'); ?> pb-e15 md:pb-e40 ml:pb-e50 lg:pb-e70">
  <div class="">

    <!-- title -->
    <div class="container">
      <h2 class="text-h1 md:text-h2 pb-e45 text-center">More Wellness Trends 2021<h2>
    </div>

    <!-- grid -->
    <div class="trends-2021-more-posts__grid justify-center" data-module-init="trends-2021-more-posts" data-posts-length="<?=$length?>">
      <?php foreach ($children->posts as $child): if($child->ID === $post->ID) continue; ?>
        <div class="px-e15 md:px-e16 ml:px-e30 lg:px-e38 trends-2021-more-posts__grid__cell">
          <div>
            <?php
            brrl_the_module('trends-2021/trends-2021-post-card', array(
              'post' => $child,
              'format' => 'grid',
              'width' => 200,
              'title_class' => 'mt-e15 text-h5',
              'add_scroll_parallax' => false,
              'mouse_parallax_amount' => 4
            ));
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>