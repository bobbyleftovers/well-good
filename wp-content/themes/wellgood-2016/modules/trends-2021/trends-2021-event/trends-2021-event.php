<?php
if(!$active) return;
if($image) $image = $image['url'];
$pretitle = trim(strip_tags($innerBlocks[0]['innerHTML']));
if($pretitle === '') $pretitle = 'Wellness Trends 2021 event';
?>
<div class="trends-2021-event w-1/1 text-gray-dark">
  <div class="trends-2021-event__flex flex flex-col sm:flex-row">

    <!-- img -->
    <div class="trends-2021-event__img sm:order-2 relative flex-grow">
      <div 
        class="absolute-full bg-cover bg-center bg-no-repeat" 
        style="background-image:url(<?=$image?>)">
      </div>
      <div class="trends-2021-event__img__padding w-1/1"></div>
    </div>

    <!-- text -->
    <div class="trends-2021-event__inner sm:order-1 relative text-left py-e40 flex-grow flex items-center">
        <div class="container z-10 relative">
            <div class="trends-2021-event__content">
              <div class="text-label mb-e10"><span class="text-gray-dark"><?= $pretitle ?></span></div>
              <h3 class="text-h3 mb-e4"><?= strip_tags($innerBlocks[1]['innerHTML']) ?></h3>
              <p class="text-default mb-e0 mt-e0"><?= strip_tags($innerBlocks[2]['innerHTML'], '<a><i><strong><b><em>') ?></p>
              <div class="trends-2021-event__cta pt-e16"><?=$innerBlocks[3]['innerHTML']?></div>
            </div>
        </div>
        <div class="trends-2021-event__gradient absolute z-0 bottom-0 left-0">
          <div class="trends-2021-event__gradient__padding"></div>
        </div>
    </div>
  </div>
</div>