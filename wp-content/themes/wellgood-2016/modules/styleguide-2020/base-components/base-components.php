<?php

$components = array(
  'Buttons' => array(
    'base-button',
    'base-button-white',
    'base-button-play',
    'empty',
    'empty'
  ),
  'Form' => array(
    'empty',
    'empty',
    'empty'
  )
)
?>

<?php foreach ($components as $title => $group): ?>

<h3 class="text-h2-styleguide"><?= $title ?></h3>

<div class="mt-e4 grid grid-cols-3 col-gap-gutter row-gap-gutter">
  <?php foreach ($group as $component): ?>
    <div>
      <?php include($component.'.php'); ?>
    </div>
  <?php endforeach; ?>
</div>

<?php endforeach; ?>
