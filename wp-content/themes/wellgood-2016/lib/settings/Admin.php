<?php

namespace WG\Settings;
use \WG\Settings\Scripts;

class Admin {

	function __construct() {
		add_action( 'admin_init', array( $this, 'add_editor_styles' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'mce_before_init_insert_formats' ) );
		add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ) );
		add_action( 'admin_menu', array( $this, 'remove_menu_comments' ) );
		add_action( 'admin_init', array( $this, 'remove_link_manager' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'add_editor_cta_button' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'add_editor_form_button' ) );
		add_action( 'admin_menu', array( $this, 'remove_custom_taxonomy_metaboxes' ), 100 );
		add_filter( 'manage_posts_columns', array( $this, 'set_custom_edit_post_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'custom_post_column' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'set_custom_visibility_editorial_tax' ), 20 );
		add_action( 'admin_menu', array( $this, 'set_custom_visibility_ad_tax' ), 20 );
		add_action( 'admin_menu', array( $this, 'set_custom_visibility_dev_tag' ), 20 );
		add_action( 'admin_menu', array( $this, 'set_custom_visibility_legacy_tag' ), 20 );
		add_action( 'admin_menu', array( $this, 'set_custom_visibility_assigned_categories' ), 20 );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
  }


	/**
	 * Add editor styles
	 *
	 * @return void
	 */
  function add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
	}

	/**
	 * Callback function to filter the MCE settings
	 *
	 * @param [array] $init_array
	 * @return void
	 */
	function mce_before_init_insert_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			array(
				'title' => 'Text Formats',
				'items' => array(
					array(
						'title'    => 'H1 Big',
						'classes'  => 'big',
						'selector' => 'h1',
					),
					array(
						'title'    => 'H2 Section Heading',
						'classes'  => 'module-heading',
						'selector' => 'h2',
					),
					array(
						'title'   => 'H2 Inline',
						'inline'  => 'span',
						'classes' => 'h2',
						'wrapper' => true,
					),
					array(
						'title'   => 'H3 Inline',
						'inline'  => 'span',
						'classes' => 'h3',
						'wrapper' => true,
					),
					array(
						'title'   => 'Big',
						'inline'  => 'big',
						'wrapper' => true,
					),
					array(
						'title'   => 'Small',
						'inline'  => 'small',
						'wrapper' => true,
					),
					array(
						'title'   => 'Paragraph Spacing',
						'inline'  => 'span',
						'classes' => 'paragraph_spacing',
						'wrapper' => true,
					),
					array(
						'title'   => 'Paragraph Centered',
						'inline'  => 'span',
						'classes' => 'paragraph_centered',
						'wrapper' => true,
					),
				),
			),
			array(
				'title' => 'Elements',
				'items' => array(
					array(
						'title'    => 'Circle Image',
						'classes'  => 'circle',
						'selector' => 'img',
					),
					array(
						'title'   => '20/80 Columns',
						'block'   => 'div',
						'classes' => 'columns_2080',
						'wrapper' => true,
					),
				),
			),
			array(
				'title' => 'Buttons',
				'items' => array(
					array(
						'title'    => 'Normal',
						'classes'  => 'btn',
						'selector' => 'a',
					),
					array(
						'title'    => 'Alternate Style',
						'classes'  => 'btn alt',
						'selector' => 'a',
					),
					array(
						'title'    => 'Filled',
						'classes'  => 'btn filled',
						'selector' => 'a',
					),
				),
			),
		);
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );

		// Remove unused formats
		$init_array['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3';

		return $init_array;
	}

	/**
	 * Callback function to insert 'styleselect' into the $buttons array
	 */
	function mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}

	/**
	 * Hide Comments from admin menu
	 *
	 * @return void
	 */
	function remove_menu_comments() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Removes link manager from the admin menu
	 *
	 * @return void
	 */
	function remove_link_manager() {
		$enabled = get_option( 'link_manager_enabled' );
		if ( 0 !== $enabled ) {
			update_option( 'link_manager_enabled', 0 );
		}
	}

	/**
	 * Add custom js to ad-options page
	 */
	function admin_ad_options_enqueue( $hook ) {
		if ( 'toplevel_page_ad-options' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'my_custom_script',
			get_template_directory_uri() . '/src/js/admin/ad-options.js'
		);
	}

	/**
	 * Remove custom taxonomy metabox from
	 * custom post type edit screen
	 */
	function remove_custom_taxonomy_metaboxes() {
		$post_type = 'post';
		// @WORK
		// Once editorial taxonomy is released, hide categories and tags taxonomies
		// remove_meta_box( 'tagsdiv-post_tag', $post_type, 'side' );
	}

	/**
	 * Create new admin columns
	 *
	 * @param array $columns - all current columns
	 * @return array $columns - new edited columns
	 */
	function set_custom_edit_post_columns( $columns ) {
		$n_columns = array();
		$before    = 'taxonomy-backend_tag';

		unset( $columns['tags'] );
		unset( $columns['categories'] );

		$columns['column_hero_tag']        = __( 'Hero Tag' );
		$columns['column_editorial_tags']  = __( 'Tags' );
		$columns['column_associated_tags'] = __( 'Associated Tags' );

		foreach ( $columns as $key => $value ) {
			if ( $key == $before ) {
				$n_columns['column_hero_tag']        = _e( '—' );
				$n_columns['column_editorial_tags']  = _e( '—' );
				$n_columns['column_associated_tags'] = _e( '—' );
			}
			$n_columns[ $key ] = $value;
		}
		return $n_columns;
	}

	/**
	 * Add content to columns
	 *
	 * @param array $column - column info
	 * @param int   $post_id - post id
	 */
	function custom_post_column( $column, $post_id ) {
		$cats = get_the_terms( $post_id, 'category' );

		$hero_tag               = get_field( 'hero_tag', $post_id );
		$tag1                   = get_field( 'tag_1', $post_id );
		$tag2                   = get_field( 'tag_2', $post_id );
		$column_hero_tag        = array();
		$column_editorial_tags  = array();
		$column_associated_tags = array();

		if ( $cats && ! empty( $cats ) ) :
			foreach ( $cats as $cat ) :
				$depth = get_category_depth( $cat );

				if ( $hero_tag && $cat->term_id == $hero_tag ) :
					$destination = 'column_hero_tag';
				elseif ( ( $tag1 && $cat->term_id == $tag1 ) || ( $tag2 && $cat->term_id == $tag2 ) ) :
					$destination = 'column_editorial_tags';
				else :
					$destination = 'column_associated_tags';
				endif;

				$markup         = sprintf( __( '<a href="%1$s">%2$s</a>' ), get_term_link( $cat->term_id ), $cat->name );
				$$destination[] = $markup;
			endforeach;
		endif;

		switch ( $column ) :
			case 'column_associated_tags':
				if ( ! empty( $column_associated_tags ) ) :
					_e( implode( ', ', $column_associated_tags ) );
				else :
					_e( '—' );
				endif;
				break;

			case 'column_editorial_tags':
				if ( ! empty( $column_editorial_tags ) ) :
					_e( implode( ', ', $column_editorial_tags ) );
				else :
					_e( '—' );
				endif;
				break;

			case 'column_hero_tag':
				if ( ! empty( $column_hero_tag ) ) :
					_e( implode( ', ', $column_hero_tag ) );
				else :
					_e( '—' );
				endif;
				break;
		endswitch;
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @param [string] $hook
	 * @return void
	 */
	function admin_scripts( $hook ) {
		wp_enqueue_style(
			'admin_styles',
			get_template_directory_uri() . '/src/css/admin.css'
		);

		switch ( $hook ) :
			case 'post-new.php' :
			case 'post.php' :
				wp_enqueue_script(
					'admin_script',
					get_template_directory_uri() . '/src/js/admin/post.js', array( 'jquery' )
				);
				// @WORK
				// wp_enqueue_script(
				// 	'my_custom_script',
				// 	get_template_directory_uri() . '/src/js/admin/post-hero.js'
				// );
			break;

			case 'toplevel_page_ad-options' :
				wp_enqueue_script(
					'ad_options_script',
					get_template_directory_uri() . '/src/js/admin/ad-options.js'
				);
			break;

      case 'term.php':
        switch($_GET['taxonomy']):
          case 'category':
            wp_enqueue_script( 'axios', 'https://unpkg.com/axios/dist/axios.min.js' );
            wp_enqueue_script( 'admin_rich_tag', get_template_directory_uri() . '/src/js/admin/rich-tag-page.js', array( 'jquery' ) );
            wp_enqueue_style( 'admin_rich_tag_styles', get_template_directory_uri() . '/src/css/admin/rich-tag-page.css' );
          break;
        endswitch;
      break;
		endswitch;
	}


	/**
	 * Add buttons to text editor
	 */
	function add_editor_cta_button() {
		?>
		<script type="text/javascript" charset="utf-8">
			if (typeof QTags != 'undefined' ) {
				QTags.addButton( 'eg_cta', 'CTA', '[cta href="" new-tab="false" align="center" background-color="" text-color="" text=""]' );
			}
		</script>
		<?php
	}

	/**
	 * Add buttons to text editor
	 */
	function add_editor_form_button() {
		?>
		<script type="text/javascript" charset="utf-8">
			QTags.addButton( 'eg_form', 'Form', '[form id=""]' );
		</script>
		<?php
	}

	/**
	 * Remove metaboxes for editorialtags unless
	 * user has editorialtag visibility turned on
	 */
	function set_custom_visibility_editorial_tax() {
		$user                      = wp_get_current_user();
		$permissions_editorial_tax = get_field( 'user_custom_visibility_editorial_tax', 'user_' . $user->ID );
		if ( ! $permissions_editorial_tax ) :
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
		endif;
	}

	/**
	 * Remove metaboxes for ad taxonomies unless
	 * user has ad tax permissions
	 */
	function set_custom_visibility_ad_tax() {
		$user               = wp_get_current_user();
		$permissions_ad_tax = get_field( 'user_custom_visibility_ad_tax', 'user_' . $user->ID );
		if ( ! $permissions_ad_tax ) :
			$ad_tax_metaboxes = array(
				'ad_catdiv',
				'tagsdiv-ad_tag',
			);
			foreach ( $ad_tax_metaboxes as $metabox ) :
				remove_meta_box( $metabox, 'post', 'side' );
			endforeach;
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=ad_cat' );
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=ad_tag' );
		endif;
	}

	/**
	 * Remove metaboxes for dev_tags unless
	 * user has dev_tag visibility turned on
	 */
	function set_custom_visibility_dev_tag() {
		$user                 = wp_get_current_user();
		$permissions_dev_tags = get_field( 'user_custom_visibility_dev_tags', 'user_' . $user->ID );
		if ( ! $permissions_dev_tags ) :
			$dev_tag_metaboxes = array(
				'tagsdiv-dev_tag',
			);
			foreach ( $dev_tag_metaboxes as $metabox ) :
				remove_meta_box( $metabox, 'post', 'side' );
			endforeach;
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=dev_tag' );
		endif;
	}

	/**
	 * Remove tags from sidebar menu dropdown
	 */
	function set_custom_visibility_legacy_tag() {
		$user                    = wp_get_current_user();
		$permissions_legacy_tags = get_field( 'user_custom_visibility_legacy_tags', 'user_' . $user->ID );
		if ( ! $permissions_legacy_tags ) :
			$legacy_tag_metaboxes = array(
				'tagsdiv-post_tag',
			);
			foreach ( $legacy_tag_metaboxes as $metabox ) :
				remove_meta_box( $metabox, 'post', 'side' );
			endforeach;
			remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
		endif;
	}

	/**
	 * Remove metaboxes for disclaimers unless
	 * user has disclaimer visibility turned on
	 */
	function set_custom_visibility_assigned_categories() {
		$user                            = wp_get_current_user();
		$permissions_assigned_categories = get_field( 'user_custom_visibility_assigned_categories', 'user_' . $user->ID );
		if ( ! $permissions_assigned_categories ) :
			$categories_metaboxes = array(
				'categorydiv',
			);
			foreach ( $categories_metaboxes as $metabox ) :
				remove_meta_box( $metabox, 'post', 'side' );
			endforeach;
		endif;
  }
}
