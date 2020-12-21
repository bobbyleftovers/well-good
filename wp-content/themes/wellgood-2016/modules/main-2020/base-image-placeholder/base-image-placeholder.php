<?php
  $width = $width ?? 200;
  $height = $height ?? 200;
  $crop = $crop ?? true;
  $quality = $quality ?? 50;
  $class = $class ?? '';
  $absolute = $absolute ?? true;
?>

<div 
  class="<?=$class?> base-image-placeholder <?= $absolute ? 'absolute top-0 left-0 h-1/1':'relative'?> w-1/1 z-0 bg-cover bg-no-repeat bg-center" 
  style="background-image: url(<?=wg_resize( false, $width, $height, $crop, $quality );?>)"
  >
</div>