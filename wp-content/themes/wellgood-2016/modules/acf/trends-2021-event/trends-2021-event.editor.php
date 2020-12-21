<?php
$allowed_blocks = array( 'core/paragraph', 'core/heading' );
$template = array(
    array( 'core/paragraph', array(
      'placeholder' => "Wellness trends 2021 event",
    )),
    array( 'core/heading', array(
        'placeholder' => "Title here..",
    )),
    array( 'core/paragraph', array(
      'placeholder' => "Description here...",
    )),
    array( 'core/button', array(
      'placeholder' => "Get Tickets",
    ))
);
if($image) $image = $image['sizes']['newsletter-card'];
?>
<div class="acf-trends-2021-event bg-tan border border-tan border-solid">

<?php if(!$active): ?>
  <div class="text-center p-e40 text-gray-75">
      Event Not active, activate to edit
  </div>
<?php else: ?>
  <div class="flex">
    <div class="acf-trends-2021-event__inner relative text-left pt-e20 flex-grow px-e30">
      <?php if($is_editor): ?>
        <InnerBlocks 
          allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
          template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
          templateLock="all"/>    
      <?php endif; 
      ?>
    </div>
    <div class="acf-trends-2021-event__img relative flex-grow">
      <div class="absolute-full bg-cover border border-grey-dark bg-center bg-no-repeat" style="background-image:url(<?=$image?>)"></div>
      <div class="acf-trends-2021-event__img__padding w-1/1"></div>
    </div>
  </div>
  <?php endif; ?>
</div>