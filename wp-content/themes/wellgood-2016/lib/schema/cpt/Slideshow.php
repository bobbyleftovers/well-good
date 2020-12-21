<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Slideshow extends Custom_Post_Type { 

  protected $args = array(
		'label' => 'Slideshows',
		'singular_name' => 'Slideshow',
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'menu_icon' => 'dashicons-images-alt2',
		'show_ui' => true,
		'query_var' => true,
		'menu_position' => 9,
		'rewrite'   => array( 'slug' => 'slideshow', 'with_front' => false ),
		'has_archive' => false,
		'capability_type' => 'post',
		'hierarchical' => false,
		'show_in_rest' => true,
		'supports' => array(
				'title',
				'thumbnail',
				'excerpt'
		),
		'taxonomies' => array( 'post_tag' ),
	);

	function __construct(){
		add_action('add_meta_boxes', array($this,'slideshow_add_meta_box'));
		parent::__construct();
	}

	// Add metabox to slideshow post admin, showing the shortcode to copy
	function slideshow_meta_box_markup() {
			global $post;
			echo "<p>Paste the following into a post editor to display this slideshow:</p>";
			echo "<input style=\"width: 100%;\" readonly type=\"text\" value='[slideshow id=\"$post->ID\"]'/>";
		}
	
	function slideshow_add_meta_box() {
			add_meta_box('demo-meta-box', 'Slideshow Shortcode', array($this,'slideshow_meta_box_markup'), 'slideshow', 'side', 'high', null);
		}

		

}
