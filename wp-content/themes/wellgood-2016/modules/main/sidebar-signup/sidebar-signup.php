<section class="signup-form signup-form--sidebar">

	<?php
		$signup_graphic = get_field('sidebar_sign_up_image', 'options');
		$signup_graphic_src = $signup_graphic ? $signup_graphic['sizes']['medium'] : '';
	?>

	<div class="sidebar-signup-graphic"><?php the_module('image', $signup_graphic_src, $signup_graphic_src); ?></div>

	<div class="signup-form__inner">

		<?php
		the_module('signup-form', array(
      'form_id' => 'sidebar',
      'location' => 'sidebar',
			'form_tagline' => get_field('sidebar_sign_up_tagline', 'options'),
			'edition_copy' => get_field('sidebar_sign_up_edition_copy','options')
		)); ?>
		<?php the_module('social-media-links'); ?>

	</div>

</section>
