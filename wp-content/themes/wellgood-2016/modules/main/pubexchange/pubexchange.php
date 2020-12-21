<?php
global $post;
$module_name = isset( $post->pubexchange_field ) ? $post->pubexchange_field : 'pubexchange_below_content_5';
?>
<section class="post-grid pubexchange">
	<p class="module-heading post-grid__headline">Promoted Stories</p>
	<div id="<?= $module_name ?>" class="pubexchange_module pubexchange_embed" data-pubexchange-module-id="1676"></div>
	<script>(function(w, d, s, id) {
	  w.PUBX=w.PUBX || {pub: "well__good", discover: false, lazy: true};
	  var js, pjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id; js.async = true;
	  js.src = "//main.pubexchange.com/loader.min.js";
	  pjs.parentNode.insertBefore(js, pjs);
	}(window, document, "script", "pubexchange-jssdk"));</script>
</section>