<?php
  $title = $title ?? false;
  $class = $class ?? '';
?>

<div class="posts-grid-more" data-module-init="posts-grid-more">
  <div v-cloak>
    <div class="px-gutter1/2 lg:w-1/4 sm:w-1/2 w-full mb-e25" v-for="post in posts">
      <?php brrl_the_module('main-2020/post-card', array(
        'is_vue' => true
      )); ?>
    </div>
  </div>
</div>
