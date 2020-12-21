<?php
/**
 * Tag card
 * 
 * $vars['article'] (array) -   post info
 * $vars['class']   (array) -   extra post card classes
 * 
 * @package Well_Good
 * @author BarrelNY
 * @since 12.12.0
 */


$article = $vars['article'];
$class = array_key_exists( 'class', $vars ) && $vars['class'] ? $vars['class'] : array();
$share_buttons = array_key_exists( 'share_buttons', $vars ) && $vars['share_buttons'] === TRUE ? TRUE : FALSE;
$include_excerpt = array_key_exists( 'include_excerpt', $vars ) && $vars['include_excerpt'] === TRUE ? TRUE : FALSE;
$id = $article->ID;
$permalink = get_permalink( $id );
$override = get_field( 'override_automatic_title_casing', $article->ID );
$title = verify_title_case( $article->post_title, $article->post_date, $override );
$excerpt = ( $class == 'featured' || $include_excerpt ) ? get_the_excerpt( $id ) : '';
$author = get_the_author_meta( 'display_name', $article->post_author );
$author_url = get_author_posts_url( $article->post_author );
$content = $article->post_content;
$image_size = 'article-retina';
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $image_size );
$image_url = $image ? @$image[0] : '';
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'article-retina' );
$retina_url = $image ? @$image[0] : '';
$url_target = get_field('_pprredirect_newwindow', $id);
$is_video = has_tag('video', $id) || has_term( 'video', 'backend_tag', $id ) ? true : false;
$is_branded = article_is_branded( $id );

$classes = ['new-article-card'];
if ( $class ) :
	array_push( $classes, $class );
endif;
?>

<article class="<?php echo implode( ' ', $classes ); ?>">
	<div class="new-article-card__image">
    <?php if ( $share_buttons ) : ?>
      <ul class="new-article-card__share">
        <?php
        $facebook_share_link = '//www.facebook.com/sharer/sharer.php?u=' . urlencode( $permalink );
        $twitter_share_link = '//twitter.com/share?text=' . wg_esc_url( $title . ' via ' . get_twitter_handle()) .'&amp;url=' . urlencode($permalink );
        $pinterest_share_link = '//pinterest.com/pin/create/link/?url=' . urlencode($permalink) . '&amp;description=' . wg_esc_url($title) . '&amp;media=' . urlencode( get_the_post_thumbnail_url($id, 'medium') );
        ?>
        <li>
          <a class="new-article-card__share--facebook" target="_blank" href="<?php echo $facebook_share_link; ?>">
            <span class="new-article-card__share--icon icon-facebook"></span>
            <span class="new-article-card__share--network">Facebook</span>
          </a>
        </li>
        <li>
          <a class="new-article-card__share--twitter" target="_blank" href="<?php echo $twitter_share_link; ?>">
            <span class="new-article-card__share--icon icon-twitter"></span>
            <span class="new-article-card__share--network">Twitter</span>
          </a>
        </li>
        <li>
          <a class="new-article-card__share--pinterest" target="_blank" href="<?php echo $pinterest_share_link; ?>">
            <span class="new-article-card__share--icon icon-pinterest-p"></span>
            <span class="new-article-card__share--network">Pinterest</span>
          </a>
        </li>
        <li>
          <a class="new-article-card__share--copy js-copy-article-link" href="<?php echo $permalink ?>">
            <span class="new-article-card__share--icon icon-link"></span>
            <span class="new-article-card__share--network">Copy Link</span>
          </a>
        </li>
      </ul>
    <?php endif; ?>
		<a href="<?php echo $permalink; ?>" target="<?php echo $url_target; ?>">
			<?php the_module('image', array(
				'image_src' => $image_url,
				'image_src_retina' => $retina_url,
				'image_alt' => $title,
				'is_video' => $is_video
			)); ?>
		</a>
	</div>
	<div class="new-article-card__content">
		<a href="<?php echo $permalink; ?>" target="<?php echo $url_target; ?>">
			<div class="new-article-card__content--title">
				<?php echo $title; ?>
			</div>
		</a>
		<?php if ( $excerpt ) : ?>
			<div class="new-article-card__content--excerpt">
				<?= truncate_by_word( $excerpt, 20, '...' ); ?>
			</div>
		<?php endif; ?>
		<div class="new-article-card__content--meta">
			<div class="new-article-card__content--author">
				<?php if ( ! $is_branded ) : ?>
					By <a href="<?php echo $author_url; ?>"><?php echo $author; ?></a>
				<?php else : ?>
					Paid Content
				<?php endif; ?>
			</div>
			<?php if ( $share_buttons ) : ?>
				<span class="new-article-card__content--share">
					<?php echo get_svg( 'share-thin' ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
</article>
