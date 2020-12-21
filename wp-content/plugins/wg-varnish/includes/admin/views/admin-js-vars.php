<?php

$js_vars = apply_filters( 'wg_varnish_expose_to_js', array());

?>
<script>
<?php
		foreach($js_vars as $key => $value){
			echo "var $key = {";
				foreach($value as $key2 => $value2){
					echo "$key2 : $value2,";
				}
			echo "};";
        };
?>
</script>