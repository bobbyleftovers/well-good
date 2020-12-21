<?php

$spacing = array(
  'Grid spacing' => array(
    'gutter1/2' => '13px',
    'gutter' => '26px'
  ),
  'Spacing utilities' => array(
    '0' => '0',
    '1' => '4px',
    '2' => '8px',
    '3' => '10px',
    '4' => '14px',
    '5' => '20px',
    '6' => '30px',
    '7' => '50px',
    '8' => '85px',
    '9' => '90px',
    '10' => '100px',
    '11' => '110px',
    '12' => '120px',
    '13' => '130px',
    '14' => '142px',
    '15' => '150px',
    '20' => '200px',
    '30' => '300px',
  )
)
?>

<?php foreach($spacing as $title => $spacing_group): ?>
<h3 class="text-h2-styleguide"><?=$title?></h3>
<div class="flex flex-row flex-wrap">
  <?php foreach($spacing_group as $class => $pixels): ?>
    <div class="mb-e14 mr-e30">
    <?php brrl_the_module('styleguide-2020/code', array(
            'lang' => 'css',
              'code' => '.{property}-'.$class
            )); ?>
    <div class="font-code mb-e10 text-sm"><?=$pixels?></div>
    <div class="w-<?=$class?> h-<?=$class?> bg-seafoam" style="width:<?=$pixels?>;height:<?=$pixels?>;"> </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endforeach; ?>


<div class="font-sans font-sm mt-e10 text-red">
    Spacing is a "work in progress". DON'T USE THEM, YET!
  </div>
