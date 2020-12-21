<div class="modal modal-signup" data-module-init="modal-signup" data-close-elem=".modal-signup .modal-close, .modal-signup .modal-bg, .modal-link-close" data-open-delay-seconds="7">
	<?php
	$signup_graphic = get_field('modal_sign_up_image', 'options');
	$signup_graphic_src = $signup_graphic ? $signup_graphic['sizes']['large'] : '';
	$signup_disclaimer = get_field('disclaimer_text', 'options');
	?>

	<div class="signup-form--modal" style="background-image: url('<?php echo $signup_graphic_src; ?>');">
		<a href="#close" class="modal-close"></a>
		<div class="signup-form__wrapper">
			<div class="signup-form__inner">
				<div class="signup-form__thanks">
					<p class="module-heading-minimal signup-form__headline"><?php the_field( 'modal_sign_up_success_text', 'options' ); ?></p>
					<?php $success_post = get_field( 'modal_sign_up_success_post', 'options' ); ?>
					<?php if ( $success_post ): ?>
                        <a href="<?php echo esc_url( $success_post ); ?>" class="signup-form__submit"><?php the_field( 'modal_sign_up_success_button_text', 'options' ); ?></a>
					<?php endif; ?>
				</div>
                <div class="signup-form__initial">
					<p class="signup-form__intro smaller"><?php the_field('modal_sign_up_intro', 'options'); ?></p>
					<p class="signup-form__headline"><?php the_field('modal_sign_up_article_title', 'options'); ?></p>
				</div>
				<?php the_module('signup-form', array(
          'form_id' => 'modal',
          'location' => 'modal',
					'form_tagline' => get_field('modal_sign_up_excerpt', 'options'),
					'newsletter_button_text' => get_field( 'modal_sign_up_button_text', 'options' ),
					'edition_copy' => get_field('modal_sign_up_edition_copy','options')
				)); ?>
				<?php if( $signup_disclaimer ) : ?>
				<div class="signup-form__disclaimer">
					<p class="signup-disclaimer smaller"><?= $signup_disclaimer; ?></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="modal-bg"></div>
</div>
