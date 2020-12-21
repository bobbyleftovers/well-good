<div class="wrap">
<h2><?php echo esc_html(__('SmartFormat Settings')); ?></h2>
<form method="post" action="options.php" novalidate="novalidate">
<?php
require_once(SMARTFORMAT__PLUGIN_DIR . 'class.smartformat-admin.php');

settings_fields(SMARTFORMAT__ADMIN_PAGE);
do_settings_sections(SMARTFORMAT__ADMIN_PAGE);
submit_button();
?>
</form>
</div>
