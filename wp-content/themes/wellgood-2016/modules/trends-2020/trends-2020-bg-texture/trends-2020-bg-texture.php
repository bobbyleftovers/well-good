<?php
$img = get_trends_2020_bg();
?>

<style>
.trends-2020-bg-texture {
  opacity:0;
}
</style>


<div 
  class="trends-2020-bg-texture" 
  data-module-init="trends-2020-bg-texture"
  style="background-image:url(<?=$img['small']?>)"
  data-image-bg="<?=$img['big']?>"
  >
  <div 
  class="trends-2020-bg-texture__low-res" 
  style="background-image:url(<?=$img['small']?>)"
  >

  </div>
</div>