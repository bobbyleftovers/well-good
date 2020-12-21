<?php
  if(is_local() && $module['title_svg']) $module['title_svg'] = str_replace('https://wellandgood.com', 'https://'.$_SERVER['SERVER_NAME'], $module['title_svg']);
?>

<div class="trends-2020-headline waypoint" data-padding="<?= $module['title_svg'] ? '300': '60' ?>">
  <h2 class="trends-2020-headline__title trends-2020__h2">
    <span style="<?= $module['title_svg'] ? 'display:none;': '' ?>"><?= $module['title'] ?></span>
    <?php if ($module['title_svg']): ?> <img src="<?= $module['title_svg'] ?>"> <?php endif; ?>
  </h2>
  <?php if($module['description']): ?>
    <div class="trends-2020-headline__description">
      <?= $module['description'] ?>
    </div>
  <?php endif; ?>
</div>