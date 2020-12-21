<?php

$textStyles = array(
  'Main Text Styles' => array(
      "h1" => 'Main Headline H1',
      "h2" => 'Title H2',
      "h3" => 'Title H3',
      "h4" => 'Title H4',
      "h5" => 'Title H5',
      "quote" => 'Article Quote',
      "dropcap" => 'Drop Cap',
      "big" => 'Text big',
      "default" => 'Text default',
      "small" => 'Text small',
      "label" => 'Label',
      "byline" => 'Byline',
      "link" => 'Link',
      "tag" => 'Tag',
      "cta" => 'CTA'
  ),
  'Article Text Variants' => array(
    "h5--article" => 'Article Title H5',
    "caption--article" => 'Image Caption',
    "byline--article" => 'Byline'
  )
);

brrl_register_vue_component('styleguide-2020/computed-styles');

foreach ($textStyles as $title => $textStyle): ?>
<h3 class="text-h2-styleguide"><?=$title?></h3>
<div class="mt-e4">
  <?php foreach ($textStyle as $class => $name): ?>
    <div class="flex px-e14 mb-e14 items-center justify-between border-styleguide">
      <div class="text-<?=$class?>"><?=$name?></div>
      <div class="flex justify-between min-w-1/2">
        <div class="p-e10 pr-e20 font-code text-sm">
          <computed-styles :properties="['fontFamily', 'fontWeight', 'fontSize','lineHeight', 'letterSpacing', 'textTransform', 'color' ]" target-class="text-<?=$class?>" />
        </div>
        <div class="">
          <?php brrl_the_module('styleguide-2020/code', array(
              'lang' => 'css',
                'code' => '.text-'.$class
              )); ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endforeach; ?>
