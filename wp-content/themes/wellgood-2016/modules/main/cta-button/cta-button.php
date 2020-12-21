<?php
global $post;

$args = $post->cta_button_field;

$href = array_key_exists('href', $args) ? $args['href'] : '';
$color = array_key_exists('color', $args) ? $args['color'] : '';
$background = array_key_exists('background', $args) ? $args['background'] : '';
$text = array_key_exists('text', $args) ? $args['text'] : '';
$target = array_key_exists('newtab', $args) && $args['newtab'] === 'false' ? '_self' : '_blank';
$align = array_key_exists('align', $args) && $args['align'] === 'left' ? 'left' : 'center';

$style = array();
if ($color) :
    $color_hex = substr($color, 0, 1) === '#' ? $color : '#' . $color;
    $style[] = "color:{$color_hex};";
endif;
if ($background) :
    $background_hex = substr($background, 0, 1) === '#' ? $background : '#' . $background;
    $style[] = "background-color:{$background_hex};";
endif;

$classes = array( 'btn', 'cta-button' );
$classes[] = "cta-button--{$align}";
?>

<a <?php if ( $href ) echo "href=\"$href\""; ?> target="<?= $target; ?>" class="<?= implode(' ', $classes); ?>" style="<?= implode('', $style); ?>"><?= $text; ?></a>
