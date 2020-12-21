<?php
global $post;
$nominees = get_field( 'nominees' );

$winner_image = get_field( 'winner_image' );
if ( $winner_image ) {
    $image_url = $winner_image['sizes']['medium'];
    $retina_url = $winner_image['sizes']['medium_large'];
} else {
    $image = wag_get_fallback_image();
    $image_url = $image ? $image['sizes']['medium'] : '';
    $retina_url = $image ? $image['sizes']['medium_large'] : '';
}
?>
<section class="voting-state">
    <div class="meet-winner">
        <figure class="meet-winner__image">
            <?php the_module( 'image', $image_url, $retina_url, get_field( 'winner_headline' ) ); ?>
        </figure>
        <div class="meet-winner__content">
            <h5 class="meet-winner__sub-heading"><?php the_field( 'winner_pre_headline' ); ?></h5>
            <h2 class="meet-winner__heading"><?php the_field( 'winner_headline' ); ?></h2>
            <figure class="meet-winner__image meet-winner__image--mobile">
                <?php the_module( 'image', $image_url, $retina_url, get_field( 'winner_headline' ) ); ?>
            </figure>
            <p><?php the_field( 'winner_summary' ); ?></p>
            <div class="voting__social--content">
                <span class="voting-content__share-text">Share:</span>
                <a class="social-share__button social-share__button--circle social-share__button--facebook" target="_blank"
                   href="//www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()) ?>">
                    <span class="icon-facebook"></span>
                </a>
                <a class="social-share__button social-share__button--circle social-share__button--twitter" target="_blank"
                   href="//twitter.com/share?text=<?php echo wg_esc_url(get_the_title() . ' via ' . get_twitter_handle()) ?>&amp;url=<?php echo urlencode(get_the_permalink()) ?>">
                    <span class="icon-twitter"></span>
                </a>
                <a class="social-share__button social-share__button--circle social-share__button--pinterest" target="_blank"
                   href="//pinterest.com/pin/create/link/?url=<?php echo urlencode(get_the_permalink()) ?>&amp;description=<?php echo wg_esc_url(get_the_title()) ?>&amp;media=<?php echo urlencode(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>">
                    <span class="icon-pinterest-p"></span>
                </a>
                <a class="social-share__button social-share__button--circle social-share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip">
                    <span></span>
                </a>
                <a class="social-share__button social-share__button--circle social-share__button--email"
                   href="mailto:?subject=<?php echo wg_esc_url(get_the_title()); ?>&body=<?php echo wg_esc_url(get_the_excerpt() . "\n\n" . get_the_permalink()); ?>">
                    <span class="icon-paper-plane"></span>
                </a>
            </div>
        </div>
    </div>
    <h2 class="module-heading module-heading--nominations"><?php the_field( 'voting_headline' ); ?></h2>
    <div class="voting__description"><?php the_field( 'voting_description' ); ?></div>
    <?php if ($nominees): ?>
        <div class="voting-grid">
            <?php foreach ($nominees as $post): setup_postdata( $post ); ?>
                <?php the_module( 'nominee', true, true ); ?>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</section>