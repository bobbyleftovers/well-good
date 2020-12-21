<?php
  $logo_attachment_id = get_option(SMARTFORMAT__LOGO_ATTACHMENT_ID);
  $logo_attachment_url = wp_get_attachment_url($logo_attachment_id);
?>
<div>
  <div>
    <img id="<?php echo SMARTFORMAT__LOGO_ATTACHMENT_ID ?>-preview" src="<?php echo $logo_attachment_url ?>" style="max-height: 100px; max-width: 200px" />
  </div>
  <button type="button" id="<?php echo SMARTFORMAT__LOGO_ATTACHMENT_ID ?>-button" class="button media-select"><?php _e('Select logo') ?></button>
  <input type="hidden" name="<?php echo SMARTFORMAT__LOGO_ATTACHMENT_ID ?>" id="<?php echo SMARTFORMAT__LOGO_ATTACHMENT_ID ?>" value="<?php echo $logo_attachment_id ?>">
</div>
