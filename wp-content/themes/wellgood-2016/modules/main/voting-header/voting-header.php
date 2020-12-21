<?php
$hero_image_desktop = get_field( 'hero_desktop' );
$hero_image_mobile = get_field( 'hero_mobile' );
$hero_ad = get_field( 'flexible_image' );
$state = get_field( 'state' );
?>
<style>
    .voting-header__hero {
        background-image: url( '<?php echo $hero_image_mobile['sizes']['medium']; ?>' );
    }

    @media screen and (min-width: 641px) {
        .voting-header__hero {
            background-image: url( '<?php echo $hero_image_desktop['sizes']['large']; ?>' );
        }
    }
</style>
<header class="voting-header">
    <div class="voting-header__hero">
        <aside class="post__share social-share--circle voting__share">
            <div class="post__share--inner">
                <a class="social-share__button social-share__button--facebook" target="_blank" href="//www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()) ?>">
                    <span class="icon-facebook"></span>
                </a>
                <a class="social-share__button social-share__button--twitter" target="_blank" href="//twitter.com/share?text=<?php echo wg_esc_url(get_the_title() . ' via ' . get_twitter_handle()) ?>&amp;url=<?php echo urlencode(get_the_permalink()) ?>">
                    <span class="icon-twitter"></span>
                </a>
                <a class="social-share__button social-share__button--pinterest" target="_blank" href="//pinterest.com/pin/create/link/?url=<?php echo urlencode(get_the_permalink()) ?>&amp;description=<?php echo wg_esc_url(get_the_title()) ?>&amp;media=<?php echo urlencode( get_the_post_thumbnail_url(get_the_ID(), 'medium') ); ?>">
                    <span class="icon-pinterest-p"></span>
                </a>
                <a class="social-share__button social-share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip">
                    <span></span>
                </a>
                <a class="social-share__button social-share__button--email" href="mailto:?subject=<?php echo wg_esc_url( get_the_title() ); ?>&body=<?php echo wg_esc_url( get_the_excerpt() . "\n\n" . get_the_permalink() ); ?>">
                    <span class="icon-paper-plane"></span>
                </a>
                <?php // TODO: Backend code for short urls
                sprintf('<a class="%1$s %1$s--link" target="_blank" href="%2$s">%3$s</a>',
                    "social-share__button",
                    "#",
                    '<span class="icon-link"></span>'
                ); ?>
            </div>
        </aside>

    </div>
    <div class="voting-header__box voting-background-color">
        <div class="voting-header__container">
            <h1 class="post__title voting-header__title"><?php the_field( 'intro_headline' ); ?></h1>
            <div class="voting-header__intro post__wysiwyg"><?php the_field( 'intro_description' ); ?></div>
            <?php if ( $hero_ad && $state == 'voting-open' ): ?>
                <a class="voting-header__ad" href="<?php the_field( 'flexible_link' ); ?>">
                    <img src="<?php echo $hero_ad['url']; ?>" alt="">
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
