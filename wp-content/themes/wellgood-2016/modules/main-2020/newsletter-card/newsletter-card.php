<?php
if($bg_image){
  $bg_image = $bg_image['sizes']['newsletter-card'];
}
$description = $description ?? '';
$title = $title ?? '';
?>

<div class=" newsletter-card relative bg-tan-light w-full bg-center bg-cover bg-no-repeat p-e10 ml:p-e10 lg:p-e22 pb-e33 ml:pb-e33 flex flex-col justify-between" style="background-image: url(<?=$bg_image?>)">
  <div class="newsletter-card__padding w-full text-center"></div>
  <div class="">
    <div class="text-h5 text-gray text-center mb-e8"><?=$title?></div>
    <div class="text-small text-gray text-center mb-e28"><?=$description?></div>
    <div class="h-auto w-full">
      <?php brrl_the_module('main-2020/newsletter-form',array(
        'style' => 'white',
        'location' => 'inline',
        )); ?>
    </div>
  </div>
</div>
