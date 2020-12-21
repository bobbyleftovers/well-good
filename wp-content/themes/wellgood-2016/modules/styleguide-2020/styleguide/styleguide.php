<?php

$modules = array(
  'Typography' => 'styleguide-2020/typography',
  'Colors' => 'styleguide-2020/colors',
  'Layout' => 'styleguide-2020/layout',
  'Components' => 'styleguide-2020/components',
  'Templating' => 'styleguide-2020/templating',
  'Image Sizes' => 'styleguide-2020/image-sizes'
);

?>

<div data-module-init="styleguide">

<?php

//MENU TOP

brrl_the_module('styleguide-2020/styleguide-menu', array('modules' => $modules, 'class' => 'border-t-0'));


//PAGES

foreach($modules as $title => $module): ?>

  <div class="container mt-e20" v-show="currentRoute == '<?= $title ?>'">

    <?php brrl_the_module($module); ?>

  </div>

<?php
endforeach;
?>
</div>
