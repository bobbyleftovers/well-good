<?php
    global $post;
    $custom_title = get_sub_field('custom_title');
    $default_title = isset($post->spotlight_header_field) ? $post->spotlight_header_field : '';
    $spotlight_sponsor_title = get_sub_field('sponsor_title');
    $spotlight_sponsor_logo = get_sub_field('sponsor_logo');
    $spotlight_sponsor_link = get_sub_field('sponsor_link');
    $learn_more_link = get_sub_field('archive_link');
    $learn_more_title = get_sub_field('archive_title');
?>
<div class="module-heading series-spotlight-heading">
    <span class="wellness-experts__headline series-spotlight__headline"><?= ( !empty( trim($custom_title) ) ) ? $custom_title : $default_title; ?></span>

    <?php if($spotlight_sponsor_link): ?><a class="collection__sponsor" href="<?= $spotlight_sponsor_link ?>"><?php endif; ?>
        <?php if( $spotlight_sponsor_title ): ?><span class="collection__sponsor-title"><?= $spotlight_sponsor_title ?></span><?php endif; ?>
        <?php if($spotlight_sponsor_logo): ?><img src="<?= $spotlight_sponsor_logo['url'] ?>" alt="<?= esc_attr( $spotlight_sponsor_title ) ?>" class="collection__sponsor-logo" /><?php endif; ?>
    <?php if($spotlight_sponsor_link): ?></a><?php endif; ?>

    <?php if(( $spotlight_sponsor_title || $spotlight_sponsor_logo) && $learn_more_title): ?><span class="collection-sponsor__archive-divider"></span><?php endif; ?>

    <?php if($learn_more_link): ?><a class="collection__archive-link series-spotlight__archive-link" href="<?= $learn_more_link ?>"><?php endif; ?>
        <?php if($learn_more_title): ?><span class="collection__archive-title"><?= ($learn_more_title) ? $learn_more_title : 'Learn More' ?></span><?php endif; ?>
    <?php if($learn_more_link): ?></a><?php endif; ?>
</div>
