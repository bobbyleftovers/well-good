<?php
$id = get_the_id();
$data = json_encode( get_datalayer_data($id), JSON_HEX_APOS|JSON_UNESCAPED_SLASHES ); 
?>

<script>
	function createUUID() {
		var pow = Math.pow(10, 10);
		var uuid = Math.floor(Math.random() * pow) + '.' + Math.floor(Math.random() * pow);
		return uuid;
	}
	function findPPID() {
		if (!localStorage.getItem('ppid')) {
			ppid = createUUID();
			localStorage.setItem('ppid', ppid);
			return ppid;
		} else {
			return localStorage.getItem('ppid');
		}
	}
	var ppid = findPPID() || '';
	if (!dataLayer) {
		var dataLayer = []
	}
	var data = <?= $data; ?>;
	data.lg_uuid = ppid; 
	dataLayer.push(data);
</script>
