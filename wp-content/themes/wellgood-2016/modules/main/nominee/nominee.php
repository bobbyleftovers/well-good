<?php
global $post, $cookie_key;

$cookie_key = $GLOBALS["cookie_key"];
$closed = isset( $post->nominee_sub_field ) ? $post->nominee_sub_field : false;
$display_vote_meter = isset($post->nominee_sub_sub_field) ? $post->nominee_sub_sub_field : false;
$campaign_id = isset($post->nominee_sub_sub_sub_field) ? $post->nominee_sub_sub_sub_field : false;

if ( has_post_thumbnail() ) {
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id, 'medium')[0];
    $retina_url = wp_get_attachment_image_src($image_id, 'medium_large')[0];
} else {
    $image = wag_get_fallback_image();
    $image_url = $image ? $image['sizes']['medium'] : '';
    $retina_url = $image ? $image['sizes']['medium_large'] : '';
}
$link = get_field( 'link' );
$description = get_field( 'description' );
$voted = empty( $_COOKIE[ $cookie_key ] ) ? false : true;
$class = 'nominee__button';
$disabled = '';
$label = 'Vote Now';

if ( $voted ) {
    if ( get_the_ID() == (int) $_COOKIE[ $cookie_key ] ) {
        $class .= ' nominee__button--voted';
        $label = 'Voted';
    } else {
        $label = 'Already Voted';
    }

    $disabled = 'disabled="disabled"';
} else {
    $class .= ' voting-button';
}
?>

<article class="nominee">
    <?php if ($link): ?>
        <a href="<?php echo $link ?>" target="_blank">
    <?php endif; ?>
    <figure class="nominee__image">
        <?php the_module('image', $image_url, $retina_url, get_the_title()); ?>
        <span class="nominee__learn-more">Learn More</span>
        <span class="nominee__plus voting-background-color"></span>
        <span class="nominee__plus voting-background-color"></span>
    </figure>
    <?php if (!$closed && $display_vote_meter): ?>
        <div class="nominee__votes">
            <div class="nominee__bar">
                <span style="width: <?php the_vote_ratio($campaign_id); ?>%;" class="voting-background-color nominee__bar-ratio--<?php the_ID(); ?>"></span>
            </div>
            <span class="nominee__count--<?php the_ID(); ?>"><?php the_vote_ratio($campaign_id); ?>%</span>
        </div>
    <?php endif; ?>
    <h3 class="nominee__heading"><?php the_title(); ?></h3>
    <?php if ($description): ?>
        <div class="nominee__description">
            <?php echo $description; ?>
        </div>
    <?php endif; ?>
    <?php if (!$closed): ?>
        <?php if ($link): ?>
            </a>
        <?php endif; ?>

        <button class="<?php echo esc_attr( $class ); ?>"
            <?php echo $disabled; ?>
            data-nominee-id="<?php the_ID(); ?>"
            data-vote-count=".nominee__count--<?php the_ID(); ?>"
            data-vote-bar=".nominee__bar-ratio--<?php the_ID(); ?>"
        ><?php echo $label; ?></button>
    <?php elseif ($link): ?>
        </a>
    <?php endif; ?>
    <?php if (current_user_can('manage_options')): $count = get_vote_count(); ?>
        <div class="nominee__vote-count"><?php echo $count . ' ' . _n( 'Vote', 'Votes', $count ); ?></div>
    <?php endif; ?>
</article>

