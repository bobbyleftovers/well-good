<?php
$components = array(
  'Buttons' => array(
    array(
      'name' => 'base-button',
      'variant' => '',
      'width' => '1/2',
    ),
    array(
      'width' => '1/2',
      'name' => 'base-button',
      'variant' => 'white',
    ),
    array(
      'width' => '1/2',
      'name' => 'base-button-play',
    ),
  ),

  'Post Cards' => array(
    array(
      'name' => 'post-card',
      'width' => '1/2'
    ),
    array(
      'name' => 'post-card',
      'variant' => 'mini',
      'width' => '1/2'
    ),
    array(
      'name' => 'post-card',
      'variant' => 'featured',
      'width' => 'full'
    )
  ),

  'Newsletter' => array(
      array(
        'name' => 'newsletter',
        'variant' => '',
        'width' => '1/2'
      ),
      array(
        'name' => 'newsletter',
        'variant' => 'white',
        'width' => '1/2'
      ),
      array(
        'name' => 'newsletter',
        'variant' => 'drawer',
        'width' => '1/2'
      )
  ),

  'Drop Cap' => array(
    array(
      'name' => 'dropcap',
      'width' => 'full'
    )
  )
)
?>

<div class="flex flex-wrap">
  <div class="w-full sm:w-1/4 md:w-1/5 lg:w-1/6 sm:pt-e30 pb-e15">
    <div class="sticky top-e120 header:top-e140">
      <?php foreach ($components as $title => $group): ?>
        <div class="font-sans uppercase text-tag mb-e15 last:mb-0">
          <span class="cursor-pointer text-gray-60 hover:text-seafoam-dark transition duration-300" @click="scrollTo('#<?=sanitize_title($title)?>')"><?= $title ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="w-full sm:w-3/4 md:w-4/5 lg:w-5/6">
    <?php foreach ($components as $title => $group): ?>

    <h3 class="text-h2-styleguide first:mt-e30 mt-e150 relative">
      <?= $title ?>
      <div class="rich-tag-module__anchor top-0 transform -translate-y-header-sm header:-translate-y-header-lg absolute" id="<?=sanitize_title($title)?>"></div>
    </h3>

    <div class="mt-e4 flex flex-wrap mb-e60 -mx-e10">
      <?php foreach ($group as $component):
        if(!isset($component['variant']) || $component['variant'] == '') $component['variant'] = '';
        else $component['variant'] = '-'.$component['variant'];
        ?>
        <div class="w-full md:w-<?=$component['width']?> p-e10">
          <div>
            <?php include($component['name'].'/'.$component['name'].$component['variant'].'.php'); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
