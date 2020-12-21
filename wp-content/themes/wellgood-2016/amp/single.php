<?php
/**
 * Single view template.
 *
 * @package AMP
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */

$this->load_parts( array( 'html-start' ) );
$post_id = get_the_ID();
$show_featured_image = get_field( 'show_featured_image', $post_id ) == 'yes' ? true : false;
$template_name = get_field( 'post_hero_type', $post_id );
$nextPages = [];
try {
  $nextPages = WG\API\REST\Infinite_Scroll::get_next_pages_amp($post_id);
} catch (\Throwable $th) {}

?>
<div class="amp-wp-header-sticky" next-page-hide>
  <?php $this->load_parts( array( 'header' ) ); ?>
</div>

<article class="amp-wp-article">
	<header class="amp-wp-article-header">
    <?php $this->load_parts( array( 'partner-label' ) ); ?>
		<h1 class="amp-wp-title"><?php echo esc_html( $this->get( 'post_title' ) ); ?></h1>
		<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author', 'meta-time' ) ) ); ?>
	</header>

  <?php if ( $template_name == 'video' ): ?>
    <?php $this->load_parts( array( 'video-hero' ) ); ?>
  <?php else: ?>
	  <?php if ($show_featured_image) {
      $this->load_parts( array( 'featured-image' ) );
    } ?>
  <?php endif; ?>

	<div class="amp-wp-article-content">
		<?php echo $this->get( 'post_amp_content' ); // WPCS: XSS ok. Handled in AMP_Content::transform(). ?>
    <?php $this->load_parts( array( 'experts' ) ); ?>
	</div>

	<footer class="amp-wp-article-footer">
		<?php $this->load_parts( apply_filters( 'amp_post_article_footer_meta', array( 'meta-taxonomy', 'meta-comments-link' ) ) ); ?>
  </footer>

  <amp-next-page max-pages="10">
    <script type="application/json">
      <?= json_encode($nextPages); ?>
    </script>
    <div separator class="amp-next-page-custom-separator">
      <template type="amp-mustache">
      </template>
    </div>

    <div recommendation-box>
      <template type="amp-mustache">
        <div class="recommendation-box-content"></div>
      </template>
    </div>
  </amp-next-page>
</article>


<?php $this->load_parts( array( 'footer' ) ); ?>

<?php
$this->load_parts( array( 'html-end' ) );
