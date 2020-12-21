<?php
$has_ad_after = false;
if (isset($module['counter']) && $module['counter'] % 2 == 0 && ($module['total_length'] - $module['counter']+1) >= 2){
  $has_ad_after = true;
}
?>

<div class="trends-2020-article-2020 waypoint <?= $has_ad_after ? 'has-ad-after':''?>" data-padding="140">
  <div class="trends-2020__row trends-2020-article-2020__row">


    <!-- thumbnail -->
    <div class="trends-2020__col-50 trends-2020-article-2020__col">
      <div class="trends-2020-article-2020__thumbnail <?= $module['post']->thumbnail_caption ? 'has-caption':'no-caption' ?>">
        <div class="trends-2020-article-2020__thumbnail_bg" data-image-bg="<?=$module['post']->thumbnail?>"></div>
        <div class="trends-2020-article-2020__thumbnail__padding"></div>
      </div>
      <?php if (isset($module['counter']) && $module['counter']): ?>
        <div class="trends-2020-article-2020__counter counter--<?=$module['counter']?>">
          <?php foreach(str_split($module['counter']) as $digit): ?>
            <img class="trends-2020-article-2020__counter__digit digit--<?=$digit?>" src="<?=get_template_directory_uri()?>/assets/img/trends-2020-<?=$digit?>.svg">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if ($module['post']->thumbnail_caption): ?>
      <div class="trends-2020-article-2020__thumbnail__caption h6 __trends-2020__font-small">
          <?= $module['post']->thumbnail_caption ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- content -->
    <div class="trends-2020__col-50 trends-2020-article-2020__col trends-2020-article-2020__content">
      <?php if (isset($module['sponsor'])): ?>
        <div class=" trends-2020-article-2020__sponsor">
          <h5 class="h5 trends-2020__h5">Sponsored by</h5>
          <a href="<?= $module['sponsor']['sponsor_link'] ?>" target="_blank">
            <img src="<?= $module['sponsor']['sponsor_logo']['url'] ?>">
          </a>
        </div>
      <?php endif; ?>
      <h3 class=" trends-2020-article-2020__title trends-2020__h3">
        <?= $module['post']->post_title ?>
      </h3>
      <div class=" trends-2020-article-2020__description trends-2020__font-small">
        <?= $module['post']->post_excerpt ?>
      </div>
      <a class="btn filled trends-2020__btn" href="<?php the_permalink($module['post']) ?>">Read more</a>
    </div>

  </div>
</div>
