<?php
$allowed_blocks = array( 'core/paragraph', 'core/heading', 'acf/video' );
$template = array(
    array( 'core/heading', array(
        'placeholder' => 'Edit title...',
    )),
    array( 'core/paragraph', array(
      'placeholder' => 'Edit description...',
    )),
    array( 'acf/video')
);
$bg_color = get_field('color_secondary', $_POST['post_id']) ?? "rgb(251,224,215)";
if(getColorLightness($bg_color) === 'dark') {
    $color = 'text-white';
  } else {
    $color = 'text-gray-dark';
}
?>
<div class="acf-trends-video pt-e10 pb-e40 <?=$color?> <?=$is_editor ? 'is-editor':''?>" style="background-color: <?= $bg_color ?>;">
  <InnerBlocks 
    allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
    template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
    templateLock="all"/>
</div>