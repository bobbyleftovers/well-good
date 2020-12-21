<?php
global $post;
// Get post info from second module parameter
$post_info = isset($post->more_reading_field) ? $post->more_reading_field : array();
?>
<div class="more-reading">
	<a href="<?= $post_info['url'] ?>" target="<?= $post_info['target'] ?>">
		<?php if(!empty($post_info['image'])): ?><div class="more-reading-image image-module"><img src="<?= get_template_directory_uri() ?>/assets/img/spacer.gif" data-src="<?= $post_info['image'] ?>" class="image-module-img" alt="<?= esc_attr($image_alt); ?>" /></div><?php endif; ?>
		<div class="more-reading-inner">
			<h6>More Reading</h6>
			<h3><?= $post_info['title'] ?></h3>
		</div>
	</a>
</div>