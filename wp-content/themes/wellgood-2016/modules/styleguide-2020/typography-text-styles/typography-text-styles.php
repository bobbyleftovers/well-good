<?php

$textStyles = array(
  'Main Text Styles' => array(
      "h1" => 'H1 Page (large)',
      "h1--article" => 'H1 Article (small)',
      "h2" => 'H2',
      "h3" => 'H3',
      "h4" => 'H4',
      "h5" => 'H5',
      "quote" => 'Quote',
      "big" => 'Paragraph Big',
      "default" => 'Paragraph Default',
      "small" => 'Paragraph Small',
      "tag" => 'Tag',
      "label" => 'Label',
      "link" => 'Link',
      "byline" => 'Byline',
      "dropcap" => "I"
  )
);

brrl_register_vue_component('styleguide-2020/computed-styles');

foreach ($textStyles as $title => $textStyle): ?>
<h3 class="text-h2-styleguide"><?=$title?></h3>
<div class="mt-e4">
  <?php foreach ($textStyle as $class => $name): ?>
    <div class="sm:flex px-e14 mb-e14 items-center justify-between border-styleguide">
      <div class="p-e10 pt-e20 sm:py-0 text-<?=$class?>"><?=$name?></div>
      <div class="flex justify-between items-center min-w-1/2">
        <div class="pb-e20 sm:pb-e10 p-e10 pr-e20 font-code text-sm">
          <computed-styles :properties="['fontFamily', 'fontWeight', 'fontSize','lineHeight', 'letterSpacing', 'textTransform', 'color' ]" target-class="text-<?=$class?>" />
        </div>
        <div class="py-e15">
          <div class="text-tag text-right text-gray-60">CSS utility class</div>
          <?php brrl_the_module('styleguide-2020/code', array(
                'lang' => 'css',
                'code' => '.text-'.$class
              )); ?>

          <div class="text-tag text-right text-gray-60">SASS extend</div>
          <?php brrl_the_module('styleguide-2020/code', array(
                'lang' => 'css',
                'code' => '@extend %text-'.$class
              )); ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endforeach; ?>
