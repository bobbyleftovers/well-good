<div class="border-b border-tan-medium pb-e16 sm:pb-18">
  <div class="text-tag border-b border-tan-medium pt-e8 pb-e8 mb-e16 sm:mb-e18">Contents</div>

  <!-- menu -->
  <?php 
  if ( $args ) :
    foreach($args as $module): if(isset($module['title'])): ?>
      <div class="text-h5 mb-e16 ml:mb-e25 last:mb-0">
        <span class="cursor-pointer hover:text-seafoam-dark transition duration-300" @click="scrollTo('#<?=sanitize_title($module['title'])?>')"><?= $module['title'] ?></span>
      </div>
      <?php 
      endif;
    endforeach;
  endif; ?>

</div>
