<?php
global $post;

$GLOBALS["cookie_key"] = "STYXKEY_wag_voted_{$post->ID}";
$state = get_field( 'state' );
$background = get_field( 'background_pattern' );
$theme_color = get_field( 'theme_color' );
?>
<?php if ( $theme_color ): ?>
    <style type="text/css">
        .voting-background-color {
            background-color: <?php echo $theme_color; ?> !important;
        }

        .voting-color {
            color: <?php echo $theme_color; ?> !important;
        }

        .voting-button:hover {
            color: <?php echo $theme_color; ?> !important;
            border-color: <?php echo $theme_color; ?> !important;
            box-shadow: inset 0px 0px 0px 1px <?php echo $theme_color; ?> !important;
        }
    </style>
<?php endif; ?>
<article class="post voting"
    <?php if ($background): ?>
        style="background-image: url('<?php echo $background['url']; ?>');"
    <?php endif; ?>
>
    <?php the_module( "voting-header" ); ?>
    <div class="post__inner post__inner--voting">
        <div class="container post__content-wrapper voting__container">
            <div class="post__content post__content--<?php echo esc_attr( $state ); ?>">
                <main class="page-main voting__main">
                    <?php
                    the_module( 'voting-state-' . $state );
                    ?>
                </main>
            </div>
        </div>
    </div>

</article>

<div class="container container--voting">
    <?php if ( $state != 'nominations' ): ?>
        <section class="contest-rules">
            <h2 class="contest-rules__heading"><?php the_field( 'contest_rules_headline' ); ?></h2>
            <div class="contest-rules__copy post__wysiwyg"><?php the_field( 'contest_rule_copy' ); ?></div>
        </section>
    <?php endif; ?>
</div>


<?php
