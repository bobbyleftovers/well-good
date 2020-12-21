<?php

$colors = array(
  'Seafoam' => array(
    'seafoam-dark' => '#42676B',
    'seafoam' => '#D0E6E4',
    'seafoam-light' => '#F8FAF9'
  ),
  'Tan' => array(
    'tan-dark' => '#81756A',
    'tan' => '#E7DFD7' ,
    'tan-medium' => '#EEE7E0' ,
    'tan-light' => '#F9F5F2',
  ),
  'Gray' => array(
    'gray-dark' => '#202020',
    'gray' => '#333333',
    "gray-75" => '#666666',
    'gray-70
.{property}-gray-light' => '#707070',
    'gray-60' => '#858585',
    'gray-25' => '#C8C8C8',
    'gray-10' => '#EFEFEF'
  ),
  'red' => array(
    'red' => '#F06767'
  ),
  'White' => array(
    'white' => '#FFFFFF',
    'transparent' => '#FFFFFF00'
  )
);

foreach ($colors as $name => $range):
?>
  <h3 class="text-h2-styleguide"><?= $name ?></h3>
  <div class="mt-e4 grid grid-cols-4 col-gap-gutter row-gap-gutter">
    <?php
      foreach ($range as $class => $color):
        $rgb = HTMLToRGB($color);
        $lightness = RGBToHSL($rgb)->lightness;
      ?>

      <div class="">
        <div>
          <div style="background-color:<?= $color ?>;" class="w-full h-e140 bg-<?= $class ?> rounded flex items-center justify-center text-center <?= $lightness > 250 ? 'border-solid border border-gray-10':''?>">
            <div class="font-code text-sm <?= $lightness < 140 ? 'text-white':''?>">
              <?php if($class === 'transparent'): ?>
                Transparent
              <?php else: ?>
              <?= $color ?></br>
              <?= getRgbCode($rgb) ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div>
          <?php brrl_the_module('styleguide-2020/code', array(
            'lang' => 'css',
            'code' => '.{property}—'.$class
          )); ?>
        </div>
      </div>

    <?php endforeach; ?>
  </div>
<?php endforeach; ?>
