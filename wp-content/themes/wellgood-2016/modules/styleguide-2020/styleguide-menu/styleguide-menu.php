<div ref="styleguide-menu" class="container relative z-50 mt-e30 mb-e30 border <?= $class ?> border-l-0 border-r-0 border-solid border-gray-10 sticky top-header-sm header:top-header-lg bg-white">

  <?php

  foreach($modules as $title => $module): ?>

      <div
      class="font-sans uppercase text-tag px-e15 h-e40 items-center justify-center inline-flex cursor-pointer"
      @click="toRoute('<?= $title ?>')"
      :class="{'border-seafoam-dark bg-seafoam': currentRoute === '<?= $title ?>'}"
      >
        <span :class="{'text-seafoam-dark': currentRoute === '<?= $title ?>', 'text-gray-60': currentRoute !== '<?= $title ?>'}">
          <?=$title?>
        </span>
      </div>

  <?php
  endforeach;

  ?>

</div>
