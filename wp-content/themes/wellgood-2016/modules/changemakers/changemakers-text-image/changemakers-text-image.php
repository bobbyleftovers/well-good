<?php
if($image) $image = $image['sizes']['newsletter-card'];
?>
<div class="changemakers-text-image pt-e60 pb-e30">
  <div class="flex">

    <!-- Text -->
    <div class="changemakers-text-image__inner relative text-left pt-e18 flex-grow">
        <h3 class="text-h1 mb-e10 italic changemakers-text-image__name">
          <?= strip_tags($innerBlocks[0]['innerHTML']); ?>
        </h3>

        <div class="text-big changemakers-text-image__content">
          <?= strip_tags($innerBlocks[1]['innerHTML']); ?>
        </div>
    </div>

    <!-- Image -->
    <div class="changemakers-text-image__img relative flex-grow">
      <div class="absolute-full bg-cover border border-grey-dark bg-center bg-no-repeat" <?php if($image): ?>style="background-image:url(<?=$image?>)"<?php endif; ?>></div>
      <div class="changemakers-text-image__img__padding w-1/1"></div>
    </div>
  </div>

  <!-- Footnote -->
  <?php if($footnote): ?>
    <div class="changemakers-text-image__footnote text-center text-default mt-e40">
      <?=$footnote?>
    </div>
  <?php endif; ?>
</div>