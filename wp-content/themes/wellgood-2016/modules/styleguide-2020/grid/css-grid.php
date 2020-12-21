<h3 class="text-h2-styleguide">CSS Grid Layout</h3>

<div class="font-sans text-gray text-sm mt-e4">
  <div class="grid grid-cols-3 col-gap-gutter row-gap-gutter">
    <div class="bg-seafoam h-e150 p-e20">1</div>
    <div class="bg-seafoam p-e20">2</div>
    <div class="bg-seafoam p-e20">3</div>
  </div>

  <div class="mt-gutter grid grid-cols-6 col-gap-gutter row-gap-gutter mt-gutter mb-gutter">
    <div class="bg-seafoam h-e150 p-e20">4</div>
    <div class="bg-seafoam p-e20">5</div>
    <div class="bg-seafoam p-e20">6</div>
    <div class="bg-seafoam p-e20">7</div>
    <div class="bg-seafoam p-e20">8</div>
    <div class="bg-seafoam p-e20">9</div>
  </div>
</div>

<?php
brrl_the_module('styleguide-2020/code', array(
'lang' => 'html',
'code' => '<div class="container">
  <div class="grid grid-cols-3 col-gap-gutter row-gap-gutter">
    <div>1</div>
    <div>2</div>
    <div>3</div>
  </div>
  <div class="grid grid-cols-6 col-gap-gutter row-gap-gutter mt-gutter">
    <div>4</div>
    <div>5</div>
    <div>6</div>
    <div>7</div>
    <div>8</div>
    <div>9</div>
  </div>
</div>'
)); ?>
