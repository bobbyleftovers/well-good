<?php
/**
 * Featured Content Promo
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 14.12.2
 */


$hero_tag = get_field( 'hero_tag', $post_id );
$vertical = get_vertical( $hero_tag );

$content = get_featured_content_promo( $vertical, $post_id ) ?? array();
$content_url = array_key_exists( 'url', $content ) ? $content['url'] : '';
$content_image = array_key_exists( 'image', $content ) ? $content['image']['sizes']['medium_large'] : '';
$content_title = array_key_exists( 'title', $content ) ? $content['title'] : '';
$content_sponsor = array_key_exists( 'sponsor', $content ) ? $content['sponsor'] : '';
$content_description = array_key_exists( 'description', $content ) ? $content['description'] : '';
$content_cta = array_key_exists( 'cta_text', $content ) ? $content['cta_text'] : '';
?>


<?php if ( $content_title && $content_url ) : ?>
<div class="featured-content-promo-wrapper">
  <div class="text-label text-center mt-e40 mb-e20">
    Featured Content
  </div>
  <div class="featured-content-promo relative bg-tan-light w-auto mr-e12 mb-e40 outline-shadow">
    <div class="flex h-full w-full">
      <?php if(is_amp_endpoint()):?>
      <img src="<?= $content_image; ?>" alt="<?= isset($content['image']) ? $content['image']['alt'] : $content_title; ?>">
      <?php else: ?>
        <div class="featured-content-promo__image h-full bg-cover bg-center flex-grow-0 flex-shrink-0" style="background-image:url(<?= $content_image; ?>)"></div>
      <?php endif;?>

      <div class="flex flex-col w-full flex-grow flex-shrink justify-center items-center mt-e40 mb-e50 mx-e18 featured-content-promo-text">

        <?php if ( $content_sponsor ) : ?>
          <div class="text-label text-center mb-e8"><?= $content_sponsor; ?></div>
        <?php endif; ?>

        <div class="text-h3 text-center featured-content-promo-title mb-e8"><?= $content_title; ?></div>

        <?php if ( $content_description ) : ?>
          <div class="text-default text-center mx-e8"><?= $content_description; ?></div>
        <?php endif; ?>

        <?php if ( $content_url && $content_cta ) : ?>
          <div class="flex justify-center mt-e20 featured-content-promo-btns">
            <?php brrl_the_module( 'main-2020/base-button', array(
              'text' => $content_cta,
              'tag' => 'a',
              'type' => 'primary',
              'href' => $content_url,
              'target' => '_blank'
            ) ); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
