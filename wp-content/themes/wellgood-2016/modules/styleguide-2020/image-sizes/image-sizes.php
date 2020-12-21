<?php

global $_wp_additional_image_sizes;

$sizes = $_wp_additional_image_sizes;

uasort($sizes, function($a, $b) {
  return $a['width'] <=> $b['width'];
});

foreach($sizes as $key => $size):?>
<div class="mb-e20">
  <h3 class="text-h2-styleguide" style="text-transform:none;"><?=$key?></h3>
  <div class="font-code text-sm mb-e30">
    Width: <?= $size['width'] ?><br>
    Height: <?= $size['height'] ?><br>
    Crop: <?= var_dump($size['crop']) ?><br>
  </div>
  <img v-if="currentRoute == 'Image Sizes'" :src="'https://source.unsplash.com/random/<?=$size['width']?>x<?=$size['height']?>'" class="mb-e30">
</div>
<?php endforeach;
