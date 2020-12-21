<?php

if(isset($prev) && $prev && $prev['blockName'] === 'trends-2021/trends-2021-advertisement'){
  $spacing_top = 'pt-e0';
} else {
  $spacing_top = 'pt-e40 md:pt-e45 ml:pt-e60 lg:pt-e120';
}
?>

<div class="trends-2021-sponsors <?= $spacing_top ?>  pb-e30 md:pb-e45 ml:pb-e65 ld:pb-e55">
  <div class="container">
    <div class="trends-2021-sponsors__title mb-e28 md:mb-e35 ml:mb-e38 lg:mb-e40">
      <h2 class="text-h3 lg:text-h2 text-center"><?=$title?></h2>
    </div>
    <div class="trends-2021-sponsors__logos text-center">
        <?php foreach($logos as $logo): ?>
          <?php if($logo['caption'] && $logo['caption'] != ''): ?><a href="<?=$logo['caption']?>" target="_blank"><?php endif; ?>
            <img src="<?=$logo['url']?>" class="trends-2021-sponsors__img mb-e25 mx-e60 _hidden lg:inline-block" width="<?=$logo['width'] / 2?>" height="<?=$logo['height'] / 2?>">
            <img src="<?=$logo['url']?>" class="trends-2021-sponsors__img mb-e22 mx-e25 ml:mx-e13 _hidden ml:inline-block lg:hidden" width="<?=$logo['width'] / 2 * 0.7?>" height="<?=$logo['height'] / 2?>">
            <img src="<?=$logo['url']?>" class="trends-2021-sponsors__img mb-e25 mx-e12 _hidden md:inline-block ml:hidden" width="<?=$logo['width'] / 2 * 0.53?>" height="<?=$logo['height'] / 2?>">
            <img src="<?=$logo['url']?>" class="trends-2021-sponsors__img mb-e22 mx-e10 ml:mx-e13 inline-block md:hidden" width="<?=$logo['width'] / 2 * 0.6?>" height="<?=$logo['height'] / 2?>">
          <?php if($logo['caption'] && $logo['caption'] != ''): ?></a><?php endif; ?>
        <?php endforeach; ?>
    </div>
  </div>
</div>