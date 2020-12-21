<?php
$suppress_margin_top_when = array(
  'trends-2021/trends-2021-slideshow', 
  'trends-2021/trends-2021-spotlight', 
  'trends-2021/trends-2021-video'
);
$suppress_margin_bottom_when = array(
  'acf/trend-spotlight',
  'acf/trends-video',
  'acf/slideshow',
);
?>
</div>
<div class="trends-2021-video <?=trends_2021_spacing('pt')?> <?=trends_2021_spacing('pb')?> text-left xs:text-center <?=$hero_color?> bg-secondary--<?=$post->ID?>
  <?php if($prev && !in_array($prev['blockName'], $suppress_margin_top_when)): ?><?=trends_2021_spacing('mt')?><?php endif; ?>
  <?php if($next && !in_array($next['blockName'], $suppress_margin_bottom_when)): ?><?=trends_2021_spacing('mb')?><?php endif; ?>
">
  <div class="trends-2021-container">
    <h2 class="text-h3 mb-e25"><?= strip_tags($innerBlocks[0]['innerHTML']); ?></h2>
    <div class="text-big mb-e45"><?= strip_tags($innerBlocks[1]['innerHTML']); ?></div>
  </div>
  <div class="mb-e30 container trends-2021-video__video"><?= $innerBlocks[2]['innerHTML'] ?></div>
</div>
<div class="trends-2021-container">