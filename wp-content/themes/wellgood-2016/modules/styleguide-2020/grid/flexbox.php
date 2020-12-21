<h3 class="text-h2-styleguide">Flexbox Layout</h3>

<div class="mt-e4 flex flex-wrap -mx-gutter1/2 font-sans text-sm text-gray">
  <div class="w-1/3 px-gutter1/2 mb-gutter">
      <div class="bg-seafoam w-full h-e150 p-e20">1</div>
  </div>
  <div class="w-2/3 px-gutter1/2 mb-gutter">
    <div class="bg-seafoam w-full h-e150 p-e20">2</div>
  </div>
  <div class="w-1/2 px-gutter1/2 mb-gutter">
    <div class="bg-seafoam w-full h-e150 p-e20">3</div>
  </div>
  <div class="w-1/4 px-gutter1/2 mb-gutter">
    <div class="bg-seafoam w-full h-e150 p-e20">4</div>
  </div>
  <div class="w-1/4 px-gutter1/2 mb-gutter">
    <div class="bg-seafoam w-full h-e150 p-e20">5</div>
  </div>
</div>

<?php
brrl_the_module('styleguide-2020/code', array(
'lang' => 'html',
'code' => '<div class="container">
  <div class="flex flex-wrap -mx-gutter1/2">
    <div class="w-1/3 p-gutter1/2">
      <div> 1 </div>
    </div>
    <div class="w-2/3 p-gutter1/2">
      <div> 2 </div>
    </div>
    <div class="w-1/2 p-gutter1/2">
      <div> 3 </div>
    </div>
    <div class="w-1/4 p-gutter1/2">
      <div> 4 </div>
    </div>
    <div class="w-1/4 p-gutter1/2">
      <div> 5 </div>
    </div>
  </div>
</div>'
)); ?>
