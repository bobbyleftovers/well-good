<?php
/**
 * Post share module
 *
 * Post share module that exists on the
 * side of the post or page content
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 4.2.0
 */


global $post;
$args = $post->post_share_field ?? array();

// @LEGACY - temporary for legacy form
$mobile = array_key_exists( 'mobile', $args ) && $args['mobile'] === true ? true : false;

$post_share_classes = array( 'post-share flex justify-center relative bg-transparent text-justify bottom-0 md:bottom-auto left-0 md:left-auto right-0 md:right-auto z-10' );
if ( $mobile ) :
  $post_share_classes[] = 'post-share--mobile';
endif;

$post_share_attrs = compile_attrs( array(
  'class' => $post_share_classes,
  'data-module-init' => 'post-share'
) );
$button_classes = array( 'post-share__button inline-block mx-e4 py-e11 text-center text-seafoam-dark no-underline leading-4 border-0' );

$icon_classes = array(
  'text-sm',
  'sm:text-base'
);

$buttons = array(
  'facebook' => array(
    'attrs' => compile_attrs( array(
      'class' => $button_classes,
      'target' => '_blank',
      'href' => '//www.facebook.com/sharer/sharer.php?u=' . urlencode( get_the_permalink() ),
      'data-vars-event' => 'Facebook',
      'data-vars-info' => esc_url( $_SERVER['REQUEST_URI'] )
    ) ),
    'icon' => compile_attrs( array(
      'class' => array_merge(
        $icon_classes,
        array( 'icon-facebook' )
      )
    ) )
  ),
  'twitter' => array(
    'attrs' => compile_attrs( array(
      'class' => $button_classes,
      'target' => '_blank',
      'href' => '//twitter.com/share?text=' . wg_esc_url( get_the_title() . ' via ' . get_twitter_handle() ) . '&amp;url=' . urlencode( get_the_permalink() ),
      'data-vars-event' => 'Twitter',
      'data-vars-info' => esc_url( $_SERVER['REQUEST_URI'] )
    ) ),
    'icon' => compile_attrs( array(
      'class' => array_merge(
        $icon_classes,
        array( 'icon-twitter' )
      )
    ) )
  ),
  'pinterest' => array(
    'attrs' => compile_attrs( array(
      'class' => $button_classes,
      'target' => '_blank',
      'href' => '//pinterest.com/pin/create/link/?url=' . urlencode( get_the_permalink() ) . '&amp;description=' . wg_esc_url( get_the_title() ) . '&amp;media=' . urlencode( get_the_post_thumbnail_url( get_the_ID(), 'medium') ),
      'data-vars-event' => 'Pinterest',
      'data-vars-info' => esc_url( $_SERVER['REQUEST_URI'] )
    ) ),
    'icon' => compile_attrs( array(
      'class' => array_merge(
        $icon_classes,
        array( 'icon-pinterest-p' )
      )
    ) )
  ),
  'email' => array(
    'attrs' => compile_attrs( array(
      'class' => $button_classes,
      'href' => 'mailto:?subject=' . wg_esc_url( get_the_title() ) . '&body=' . wg_esc_url( get_the_excerpt() . " \n\n" . get_the_permalink() ),
      'data-vars-event' => 'Email',
      'data-vars-info' => esc_url( $_SERVER['REQUEST_URI'] )
    ) ),
    'icon' => compile_attrs( array(
      'class' => array_merge(
        $icon_classes,
        array( 'icon-paper-plane' )
      )
    ) )
  )
);
?>

<aside <?= $post_share_attrs; ?>>
  <?php foreach( $buttons as $key => $button ) : ?>
    <a <?= $button['attrs'] ?>>
      <span <?= $button['icon'] ?>></span>
      <spann class="visually-hidden">Share on <?= $key ?></spann>
    </a>
  <?php endforeach; ?>
</aside>
