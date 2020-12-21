<?php
global $post;
$nominees = get_field( 'nominees' );
?>
<section class="voting-state">
    <h2 class="module-heading module-heading--nominations"><?php the_field( 'voting_headline' ); ?></h2>
    <div class="voting__description"><?php the_field( 'voting_description' ); ?></div>
    <p class="voting-closed__message"><?php the_field( 'voting_closed_message' ); ?></p>

    <?php if ($nominees): ?>
        <div class="voting-grid">
            <?php foreach ($nominees as $post): setup_postdata( $post ); ?>
                <?php the_module( 'nominee', true, true ); ?>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</section>