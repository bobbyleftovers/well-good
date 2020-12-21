<style>

  body:not(.ie) .trends-2020-article-lookback.waypoint {
    opacity:0;
  }

</style>

<div class="trends-2020-article-lookback not-waypoint" data-padding="200">

  <!-- thumbnail -->
  <div class="trends-2020-article-lookback__thumbnail">
    <div class="trends-2020-article-lookback__thumbnail__bg" data-image-bg="<?=$module['post']->thumbnail?>"></div>
    <div class="trends-2020-article-lookback__gradient"></div>
  </div>

  <!-- content -->
  <div class="trends-2020-article-lookback__content">
    <h3 class=" trends-2020-article-lookback__title trends-2020__h4">
      <?= $module['post']->post_title ?>
    </h3>
    <div class=" trends-2020-article-lookback__description trends-2020__font-small">
      <?= $module['post']->post_excerpt ?>
    </div>
    <a class="btn filled trends-2020__btn" href="<?php the_permalink($module['post']) ?>">Read more</a>
  </div>

</div>