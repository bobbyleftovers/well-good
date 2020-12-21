<div class="border-styleguide p-e14 base-component-box">
      <div class="mb-e10">
        <?php brrl_the_module('main-2020/base-button', array('tag' => 'a','text' => 'Active')); ?>
      </div>
      <div>
        <?php brrl_the_module('main-2020/base-button', array('tag' => 'a','text' => 'Disabled', 'disabled' => true)); ?>
      </div>
    </div>
    <div>
<?php brrl_the_module('styleguide-2020/code', array(
            'title' => 'PHP',
            'lang' => 'php',
            'code' => "brrl_the_module(
  'main-2020/base-button',
  array(
    'text' => 'Lorem Ipsum',
    'tag' => 'a',
    'type' => 'primary'
    'class' => '',
    'attrs' => '',
    'disabled' => false
  )
);
"
)); ?>
<?php brrl_the_module('styleguide-2020/code', array(
            'title' => 'HTML',
            'lang' => 'html',
            'code' => '<a class="text-link base-button base-button--primary">
  Button Text
</a>'
)); ?>
      </div>
