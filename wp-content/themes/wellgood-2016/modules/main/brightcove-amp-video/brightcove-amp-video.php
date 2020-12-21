<?php
if ( empty( $post->brightcove_amp_video_field ) ) {
	return;
}
$args = $post->brightcove_amp_video_field;
?>
<amp-brightcove
  data-account="<?= $args['account_id'] ?>"
  data-video-id="<?= $args['id'] ?>"
  data-player-id="<?= $args['player_id'] ?>"
  layout="responsive"
  width="480"
  height="270">
</amp-brightcove>
