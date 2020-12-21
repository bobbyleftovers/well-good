<?php

/**
 * Update Partnership Information
 *
 * This script is used to update partnership taxonomies. At the time of implementation,
 * the partners taxonomy was being managed via an ACF field. We'd prefer it to be a taxonomy.
 * This script will use the values in the provided .csv file (pulled from the website's database)
 * to update the provided taxonomy for each post.
 *
 * The script assumes that the first value of each CSV line is the post ID
 * and that the second value is the taxonomy term to be added
 *
 * @author BarrelNY
 *
 */

namespace WG\Schema\Taxonomies;

use WG\Schema\Taxonomies\Custom_Taxonomy;

class Partners extends Custom_Taxonomy { 

	protected $post_types = array('post', 'page', 'recipe');

	protected $labels = array(
		'name' => 'Partners',
		'singular_name' => 'Partners',
		'search_items' =>   'Search Partners',
		'all_items' => 'All Partners',
		'parent_item' => 'Parent Partner',
		'parent_item_colon' => 'Parent Partner:',
		'edit_item' => 'Edit Partner',
		'update_item' => 'Update Partner',
		'add_new_item' => 'Add New Partner',
		'new_item_name' => 'New Partner',
		'menu_name' => 'Partners',
		'back_to_items' => '← Back to Partners'
);

  protected $args = array(
		'hierarchical' => false,
		'show_ui' => true,
		'show_admin_column' => false,
		'show_in_rest' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'partners')
	);

	public $update_categories = false;

	public function __construct() {

		if (isset($_GET['updatePartnerTaxonomy']) && $_GET['updatePartnerTaxonomy'] == 'yes') {
			$this->update_categories = true;
		}

		if ($this->update_categories) {
			add_action('init', array( &$this, 'update_partnership_taxonomy' ) );
		}

		parent::__construct();
  }
	
		/**
	 * Check if a certain piece of content has a blacklisted Partner tag associated to it.
	 * This is introduced to exclude content from infinite scroll based on a "blacklist" of partner tags.
	 * The blacklist is managed in Theme Options as a multi-select check-box field.
	 *
	 * Current Logic:
	 * Infinite scroll should not be instantiated for any article with a tag in the blacklist
	 * Any article with a tag in the black list should be excluded from being added via infinite scroll
	 *
	 * @param INT $post_id - The ID of the post
	 */
	static function partner_is_blacklisted( $post_id ) {
		$partners = get_the_terms( $post_id, 'partners' ); // likely that this will usually have one value in it, but we should account for multiple
		$partner_blacklist = get_field('infinite_scroll_blacklist', 'options');
		$blacklisted = false;

		if ( ! empty( $partner_blacklist ) && ! empty( $partners ) ) :
			foreach ( $partners as $partner ) :
				if ( is_object( $partner ) ) :
					$partner = $partner->term_id;
				endif;
				if ( in_array( $partner, (array) $partner_blacklist ) ) :
					$blacklisted = true;
					break;
				endif;
			endforeach;
		endif;

		// If the initial article is not blacklisted, we need to define the list of blacklisted partners for
		// subsequent article queries over AJAX. If we make it a global js variable here, we can avoid additional
		// sql queries in the future by making the blacklisted partners a global js variable here
		// This will get used in `add_next_scroll_post()` as a `tag__not_in` param
		if ( ! $blacklisted ) :
			$partner_blacklist = json_encode( $partner_blacklist );
			echo "<script type=\"text/javascript\">
				var blacklistedPartners = $partner_blacklist
			</script>";
		endif;

		return $blacklisted;
	}

	public function update_partnership_taxonomy() {
		global $wpdb;

		// Load wordpress to access database
		$csv_data = array_map('str_getcsv', file( get_template_directory_uri() . '/assets/wellgood_partnership_data.csv'));
		$taxonomy = 'partners';

		foreach ($csv_data as $post) {
      $post_id = $post[0];
      $partner = $post[1];

      wp_set_object_terms($post_id, $partner, $taxonomy);
			// Report
      echo "<p style=\"color:limegreen;font-weight:bold;\">Updated partner values for post $post_id to \"$partner\"</p>";

    }
    exit;
	}

}
