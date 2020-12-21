<?php

$image = $args->hero_image;
if($image) $image = $image['sizes']['rich-tag-hero'];

?>

<div class="rich-tag-hero w-full relative <?= $image ? 'has-image text-white bg-tan-light':'no-image text-gray bg-white'?>">
<div class="rich-tag-hero__image <?= $image ? 'absolute top-0 left-0 h-full bg-cover bg-no-repeat bg-center': '' ?> w-full"
  <?php if($image): ?> style="background-image:url(<?=$image?>)"<?php endif; ?>></div>
  <div class="rich-tag-hero__title text-h1 <?= $image ? 'top-1/2 left-1/2 absolute -translate-x-1/2 -translate-y-1/2 transform': '' ?> text-center"><?= $name ?></div>
</div>
