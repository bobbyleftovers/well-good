<?php
$template = array(
  array( 'core/paragraph', array(
      'placeholder' => 'Add content...'
  ) ),
);
?>
<InnerBlocks  template="<?= esc_attr( wp_json_encode( $template ) ) ?>" templateLock="insert"/>