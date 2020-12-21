<?php
/**
 * Custom AMP post author template part.
 * 
 * More info: https://amp-wp.org/documentation/how-the-plugin-works/classic-templates/
 *
 * @package AMP
 */


$post_author = $this->get( 'post_author' );

?>
<?php if ( $post_author ) : ?>
	<a href="<?php echo get_author_posts_url($post_author->ID); ?>" class="amp-wp-meta amp-wp-byline">
		<?php if ( function_exists( 'get_avatar_url' ) ) : ?>
			<amp-img src="<?php echo esc_url( get_avatar_url( $post_author->user_email, [ 'size' => 24 ] ) ); ?>" alt="<?php echo esc_attr( $post_author->display_name ); ?>" width="24" height="24" layout="fixed"></amp-img>
		<?php endif; ?>
		<span class="amp-wp-author author vcard"><?php echo esc_html( $post_author->display_name ); ?></span>
	</a>
<?php endif; ?>
