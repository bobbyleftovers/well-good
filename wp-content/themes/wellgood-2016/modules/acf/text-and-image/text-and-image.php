<?php
$allowed_blocks = array( 'core/paragraph', 'core/heading' );
$template = array(
    array( 'core/heading', array(
        'placeholder' => "Title here..",
    )),
    array( 'core/paragraph', array(
      'placeholder' => "Description here...",
    ))
);
if($image) $image = $image['sizes']['newsletter-card'];
?>

<div class="acf-text-and-image">
  <div class="flex">
    <div class="acf-text-and-image__inner relative text-left pt-e20 flex-grow">
      <?php if($is_editor): ?>
        <InnerBlocks 
          allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
          template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
          templateLock="all"/>
          
      <?php else: ?> 
        <h3 class="text-h1 mb-e10 italic acf-text-and-image__name">
          <?= strip_tags($innerBlocks[0]['innerHTML']); ?>
        </h3>

        <div class="text-default italic acf-text-and-image__content">
          <?= strip_tags($innerBlocks[1]['innerHTML']); ?>
        </div>
      <?php endif; 
      ?>
    </div>
    <div class="acf-text-and-image__img relative flex-grow">
      <div class="absolute-full bg-cover border border-grey-dark bg-center bg-no-repeat" style="background-image:url(<?=$image?>)"></div>
      <div class="acf-text-and-image__img__padding w-1/1"></div>
    </div>
  </div>
</div>