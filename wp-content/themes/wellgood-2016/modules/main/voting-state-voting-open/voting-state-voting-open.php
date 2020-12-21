<?php
global $post;
$nominees = get_post_meta( $post->ID, 'nominees', true );
$captcha_key = get_field('recaptcha_site_key');
?>
<section class="voting-state"
         data-campaign-id="<?php echo $post->ID; ?>"
         data-module-init="voting-state-voting-open"
         data-modal-trigger=".nominee__button"
         data-modal=".voting-modal"
         data-modal-close=".voting-modal__cancel span, .voting-modal__close"
>
    <h2 class="module-heading module-heading--nominations"><?php the_field( 'voting_headline' ); ?></h2>
    <div class="voting__description post__wysiwyg"><?php the_field( 'voting_description' ); ?></div>

    <?php if ($nominees):
        $display_meter = get_field( 'voting_display_meter' );
	    $campaign_id = get_the_ID();
        ?>
        <div class="voting-grid">
            <?php foreach ($nominees as $post_id): $post = get_post((int)$post_id); setup_postdata( $post ); ?>
	            <?php the_module( 'nominee', true, false, $display_meter, $campaign_id ); ?>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>

        <div class="voting-modal">
            <div class="voting-modal__container">
                <div class="voting-modal__thanks">
                    <h2 class="voting-modal__heading"><?php the_field( 'thank_headline' ); ?></h2>
                    <p class="voting-modal__message"><?php the_field( 'thank_text' ); ?></p>
                    <div class="voting__social--content">
                        <a class="social-share__button social-share__button--circle social-share__button--facebook" target="_blank"
                           href="//www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()) ?>">
                            <span class="icon-facebook"></span>
                        </a>
                        <a class="social-share__button social-share__button--circle social-share__button--twitter" target="_blank"
                           href="//twitter.com/share?text=<?php echo wg_esc_url(get_field('share_twitter_subject')) ?>&amp;url=<?php echo urlencode(get_the_permalink()) ?>">
                            <span class="icon-twitter"></span>
                        </a>
                        <a class="social-share__button social-share__button--circle social-share__button--pinterest" target="_blank"
                           href="//pinterest.com/pin/create/link/?url=<?= urlencode(get_the_permalink()) ?>&amp;description=<?= wg_esc_url(get_the_title()) ?>&amp;media=<?= urlencode(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>">
                            <span class="icon-pinterest-p"></span>
                        </a>
                        <a class="social-share__button social-share__button--circle social-share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip">
                            <span></span>
                        </a>
                        <a class="social-share__button social-share__button--circle social-share__button--email"
                           href="mailto:?subject=<?php echo wg_esc_url(get_the_title()) ?>&body=<?php echo wg_esc_url(get_field('share_email_message')); ?>">
                            <span class="icon-paper-plane"></span>
                        </a>
                    </div>
                </div>
                <?php $vote_gating = get_field('vote_gating'); if( $vote_gating ) : ?>
                  <div class="voting-modal__form signup-form--modal">
                      <h2 class="voting-modal__heading"><?php the_field( 'subscribe_form_heading' ); ?></h2>
                      <p class="voting-modal__message"><?php the_field( 'subscribe_optin_text' ); ?></p>

                      <form action="<?php bloginfo('template_url'); ?>/lib/postup-process.php?ajax" class="signup-form__form" method="post" data-error-elem=".signup-form__result" data-loading-class="loading">
                          <div class="signup-form__fields">
                            <?php if( $captcha_key ): ?>
                            <div class="g-recaptcha" data-sitekey="<?= $captcha_key; ?>" data-callback="onCaptchaSuccess"></div>
                            <?php endif; ?>
                            <div class="signup-form__group signup-form__group__fields">
                                <input type="text" class="required signup-form__email" name="email" id="email" placeholder="Email Address">
                                <input id="signup-form__submit" type="submit" name="submit" class="signup-form__submit" value="Vote Now" <?= ( $captcha_key ) ? 'disabled' : '' ?>>
                            </div>
                            <p class="signup-form__result small error"></p>
                          </div>
                          <div class="signup-form__fields">
                              <label class="signup-form__radio"><input type="checkbox" checked="checked" disabled="disabled" value="yes"><span class="radio"></span> <?php the_field( 'subscribe_radio_text' ); ?></label>
                              <input type="hidden" name="merge_field" value="<?php the_field( 'subscribe_merge_field' ); ?>">
                              <input type="hidden" name="merge_field_value" value="<?php the_field( 'subscribe_merge_field_value' ); ?>">
                          </div>
                      </form>
                  </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
