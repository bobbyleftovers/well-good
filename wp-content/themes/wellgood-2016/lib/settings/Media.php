<?php

namespace WG\Settings;

class Media {

  function __construct() {
		//add_filter('pre_option_upload_url_path', array($this,'rewrite_uploads'));
		add_action( 'after_setup_theme', array($this, 'add_image_sizes'));
		// Remove Jetpack gallery handler
		remove_all_filters( 'post_gallery', 1002 );
		// Use our own gallery handler
		add_filter( 'post_gallery', array($this,'custom_gallery'), 99, 2 );
	}


	/**
	 * Map any urls for the Uploads to the test domain if we're in a local environment.
	 * This allows us to not download the /uploads directory and avoid copious 404 errors
	 */
	function rewrite_uploads($upload_url_path) {
		if ( is_local() ) {
			return 'https://wellandgood.com/wp-content/uploads';
		}
	}


	function custom_gallery( $output, $attr ) {
		if ( empty( $attr['type'] ) || 'slideshow' != $attr['type'] ) {
			return $output;
		}

		global $post;

		$attr = shortcode_atts( array(
			'order'   => 'ASC',
			'orderby' => 'menu_order ID',
			'id'      => $post->ID,
			'include' => '',
			'exclude' => '',
			'size'    => '',
		), $attr, 'slideshow' );

		if ( 'rand' == strtolower( $attr['order'] ) ) {
			$attr['orderby'] = 'none';
		}

		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			$attr['orderby'] = 'menu_order ID';
		}

		if ( ! $attr['size'] ) {
			$attr['size'] = 'full';
		}

		// Don't restrict to the current post if include
		$post_parent = ( empty( $attr['include'] ) ) ? intval( $attr['id'] ) : null;

		$attachments = get_posts( array(
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => - 1,
			'post_parent'    => $post_parent,
			'order'          => $attr['order'],
			'orderby'        => $attr['orderby'],
			'include'        => $attr['include'],
			'exclude'        => $attr['exclude'],
		) );

		if ( count( $attachments ) < 1 ) {
			return '';
		}

		$output = '<div class="post__slideshow" data-module-init="flickity" data-breakpoint="0" data-arrow="no" data-dots="yes">';

		foreach ( $attachments as $attachment ) {
			$attachment_image_src = wp_get_attachment_image_src( $attachment->ID, $attr['size'] );
			$attachment_image_src = $attachment_image_src[0]; // [url, width, height]
			$attachment_image_alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );

			$caption = wptexturize( strip_tags( $attachment->post_excerpt ) );

			$output .= '<figure class="post__slideshow-slide">';
			$output .= '<div class="post__slideshow-image-wrapper">';
			$output .= '<div class="post__slideshow-image" style="background-image: url(\'' . esc_url( $attachment_image_src ) . '\');" title="' . $attachment_image_alt . '""></div>';
			$output .= '<a href="//pinterest.com/pin/create/link/?url=' . urlencode( get_the_permalink() ) . '&amp;description=' . urlencode( wp_strip_all_tags( get_the_title() ) ) . '&amp;media=' . urlencode( $attachment_image_src ) . '" target="_blank" class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest"><span class="icon-pinterest-p"></span></a>';
			$output .= '</div>';
			$output .= '<figcaption class="post__slideshow-caption">' . esc_html( $caption ) . '</figcaption>';
			$output .= '</figure>';
		}

		$output .= '</div>';

		return $output;

	}

	function add_image_sizes(){
		// Thumbnail sizes
		add_image_size( 'article', 425, 285, array( 'center', 'center' ) );
    add_image_size( 'article-retina', 850, 570, array( 'center', 'center' ) );

    // Post cards
    add_image_size( 'post-card-mini', 84, 84, array( 'center', 'center' ) );
    add_image_size( 'newsletter-card', 301, 399, array( 'center', 'center' ) );
    add_image_size( 'youtube-channel-card', 600, 700, array( 'center', 'center' ) );

		// Default values for the upload media box
		update_option('image_default_align', 'center' );
		update_option('image_default_link_type', 'none' );
		update_option('image_default_size', 'medium' );

		// Photolay Photos
    add_image_size( 'photolay', 500, 2000, false );

    // Rich Tag
    add_image_size( 'rich-tag-hero', 1441, 479, array( 'center', 'center' ) );
	}

}
