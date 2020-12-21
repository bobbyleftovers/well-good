<?php
global $post;?>
<div class="renew-year-2021-slideshow pt-e30 pb-e55 md:py-e50 ml:pt-e70 ml:pt-e65 xl:pt-e90 xl:pb-e80" data-module-init="renew-year-2021-slideshow" data-size="<?= count($slideshow) ?>"><?php
  $sponsor_copy = get_field('sponsor_copy', $post->ID) ? get_field('sponsor_copy', $post->ID) : null;
  $sponsor_logo = get_field('sponsor_logo', $post->ID) ? get_field('sponsor_logo', $post->ID) : null;
  $logo =  $sponsor_logo ? '<img src="'.wg_resize($sponsor_logo['url'], 80, 'auto', false).'" class="ml-e4" />' : null;
  if($sponsor_copy && $logo) {
    echo '<div class="flex justify-center items-center w-full mb-e19"><span class="flex justify-center items-center text-center text-tag">'.$sponsor_copy.$logo.'</span></div>';
  }?>
  
  <!-- <div class="w-full block"> -->
    <div class="renew-year-2021-slideshow__flkty relative" ref="flkty">
      <?php foreach($slideshow as $cell): ?>
        <a href="<?= $cell['description'] ?>" target="_blank" class="renew-year-2021-slideshow__cell flex flex-col items-center justify-center w-1/1 md:w-1/2 ml:w-1/3">
          <div class="renew-year-2021-slideshow__cell-img-wrap flex items-center justify-center mb-e16 w-full px-e10">
            <img src="<?= wg_resize($cell['url'], 300, 300, false) ?>" />
          </div>
          <span class="text-h4 text-center"><?= $cell['caption'] ?></span>
          <span class="text-h5 text-center"><?= $cell['alt'] ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <!-- </div> -->
</div>