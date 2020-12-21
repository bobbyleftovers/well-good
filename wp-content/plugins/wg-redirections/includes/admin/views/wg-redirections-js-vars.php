<?php

$js_vars = array(
    'wg_redirections' => array(
        'admin_page' => $this->admin_page,
        'user_id' => get_current_user_id(),
        'action_new' => wp_nonce_url($this->admin_page, 'new' ),
        '_wpnonce' => wp_create_nonce('wg_redirections_nonce')
    )
)

?>
<script>
<?php
		foreach($js_vars as $key => $value){
			echo "$key = {";
				foreach($value as $key2 => $value2){
					echo "'$key2' : '$value2',";
				}
			echo "};";
        };
?>
</script>