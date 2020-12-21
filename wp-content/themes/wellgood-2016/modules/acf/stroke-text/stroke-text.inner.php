<?php
$allowed_blocks = array( 'core/paragraph' );
$template = array(
  array( 'core/paragraph', array(
      'placeholder' => 'Edit text',
  ) )
);
?>

<InnerBlocks 
    allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
    template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
    templateLock="all"/>