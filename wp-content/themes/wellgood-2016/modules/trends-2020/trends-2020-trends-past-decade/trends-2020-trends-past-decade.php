
<style>
  body:not(.ie) .trends-2020-trends-past-decade__year.waypoint {
    opacity:0;
  }
</style>

<div class="trends-2020-trends-past-decade trends-2020__row">
<?php
if($module['trends_past_decade']):
  foreach ($module['trends_past_decade'] as $year):
    if ($year['image'] && $year['link']):
    ?>

    <div class="trends-2020-trends-past-decade__year trends-2020__col-33 not-waypoint" data-padding="130">
      <a class="trends-2020-trends-past-decade__year__a"
        target="<?=$year['link']['target']?>"
        title="<?=$year['link']['title']?>"
        href="<?=$year['link']['url']?>"
        >
        <div class="trends-2020-trends-past-decade__year__label trends-2020__h2"><?= $year['label']?></div>
        <div class="trends-2020-trends-past-decade__year__bg" data-image-bg="<?=$year['image']['sizes']['medium']?>"></div>
      </a>
    </div>

    <?php
    endif;
  endforeach;
endif;
?>
</div>
