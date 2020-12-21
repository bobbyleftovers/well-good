
<div class="pt-e35 pb-e40 md:pt-e45 md:pb-e50 ml:py-e65 ld:py-e80 trends-2021-past-decade text-center">
  <div class="container">
    <h2 class="text-h1 md:text-h3 ml:text-h2 mb-e25 md:mb-e35 ml:mb-e20 lg:mb-e40">Trends from Previous Years</h2>
  </div>

  <div class="trends-2021-past-decade__slideshow" data-module-init="trends-2021-past-decade">
  <?php foreach($slide as $cell): 
    $image = wg_resize( $cell['image']['url'], 320, 394, true )
    ?>
    <div class="trends-2021-past-decade__cell">
      <a href="<?=get_the_permalink($cell['post']);?>"class="trends-2021-past-decade__cell__content">
        <div class="trends-2021-past-decade__img relative mb-e15 md:mb-e8 ml:mb-e20 lg:mb-e25">
          <div class="trends-2021-past-decade__img__padding"></div>
          <?php brrl_the_module('main-2020/base-image-placeholder');?>
          <?php the_module('image', array(
            'image_src' => $image,
            'image_src_retina' => $image,
            'image_width' => 320,
            'image_height' => 394,
          )); ?>
        </div>
        <div class="text-h4 hover:opacity-70 transition-opacity"><?= $cell['caption'] ?></div>
      </a>
  </div>
  <?php endforeach; ?>
  </div>
  <div class="clear"></div>
</div>