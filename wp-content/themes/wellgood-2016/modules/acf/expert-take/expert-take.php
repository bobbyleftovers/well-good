<?php
$allowed_blocks = array( 'core/paragraph', 'wg/plain-text' );
$template = array(
    array( 'wg/plain-text', array(
        'placeholder' => "Expert's name",
    )),
    array( 'wg/plain-text', array(
      'placeholder' => "Expert's title",
    )),
    array( 'core/paragraph', array(
      'placeholder' => "Write here the content...",
    ))
);
if(isset($image) && $image) $image = wg_resize( $image['url'], 198, 246, true, 85 );
else $image = false;
$pading = "py-e25 px-e20 sm:px-e30 md:pt-e20 md:pb-e25 ml:py-e20 md:px-e40 lg:py-e33 lg:px-e40";
?>

<div class="relative mt-e50 mb-e30 last:mb-e0">
  <div class="absolute w-1/1 top-0 left-0 -translate-y-1/2 transform acf-expert-take__label flex items-center">
    <div class="text-label px-e12">The Experts Take</div>
  </div>
  <div class="<?=$pading?> acf-expert-take flex flex-col sm:flex-row border border-solid border-t-0 justify-center items-center">

    <!--img-->
    <div class="acf-expert-take__img relative mb-e15 sm:mb-e0">
      <?php brrl_the_module('main-2020/base-image-placeholder');?>
      <div class="absolute-full bg-cover bg-center bg-no-repeat" style="background-image:url(<?=$image?>)"></div>
      <div class="relative acf-expert-take__img__padding w-1/1"></div>
      <?php if($post): ?>
        <div class="acf-expert-take__img__gradient absolute bg-accent-1--<?=$post->ID?>"></div>
      <?php endif; ?>
    </div>

    <!--text-->
    <div class="acf-expert-take__inner relative text-center sm:text-left flex-grow">
      <?php if($is_editor):?>
        <InnerBlocks 
          allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
          template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
          templateLock="all"/>
          
          
      <?php else: ?> 
        <h3 class="text-h2 md:text-h5 mb-e3 md:mb-e6 lg:mb-e10 acf-expert-take__name">
          <?= strip_tags($innerBlocks[0]['innerHTML']); ?>
        </h3>

        <div class="text-small md:text-default italic mb-e10 sm:mb-e6 md:mb-e6 lg:mb-e10 acf-expert-take__title">
          <?= strip_tags($innerBlocks[1]['innerHTML']); ?>
        </div>

        <div class="text-small md:text-default acf-expert-take__content">
          <?= strip_tags($innerBlocks[2]['innerHTML']); ?>
        </div>
      <?php endif; 
      ?>
    </div>
  </div>
</div>