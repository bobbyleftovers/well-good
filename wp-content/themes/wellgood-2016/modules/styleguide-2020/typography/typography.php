<?php
$fontFamily = array(
  "display" => 'Orpheus Pro',
  "serif" => 'Freight Text Pro',
  "sans" => 'Neue Haas Unica'
);
?>


<h3 class="text-h2-styleguide">Fonts</h3>
<div class="mt-e4 grid grid-cols-1 md:grid-cols-3 col-gap-gutter row-gap-gutter">
  <?php foreach ($fontFamily as $class => $name): ?>
  <div>
    <div class="text-h1-mobile w-100 h-e140 flex items-center justify-center text-center border-styleguide">
      <span class="font-<?=$class?>"><?=$name?></span>
    </div>
    <div>
      <?php brrl_the_module('styleguide-2020/code', array(
          'lang' => 'css',
          'code' => '.font-'.$class
        )); ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php brrl_the_module('styleguide-2020/typography-text-styles'); ?>


