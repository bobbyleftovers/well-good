<?php
if ( empty( $post->brightcove_video_field ) ) {
	return;
}
$args = $post->brightcove_video_field;
?>
<div style="padding-top: 56.25%; position: relative;">
	<video
		id="video-<?php echo uniqid(); ?>"
		style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%;"
		data-video-id="<?php echo $args['id']; ?>"
		data-account="<?php echo $args['account_id']; ?>"
		data-player="<?php echo $args['player_id']; ?>"
		data-parsely-video
		data-embed="default"
		data-application-id
		class="video-js"
		controls></video>
	<script async src="//players.brightcove.net/<?php echo $args['account_id']; ?>/<?php echo $args['player_id']; ?>_default/index.min.js"></script>
</div>
