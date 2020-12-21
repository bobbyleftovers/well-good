<?php
/**
 * Advanced Custom Fields
 *
 * This file controls the injection of Advertisements
 * and other media within `the_content` of a post
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */

namespace WG\Plugins;
use \WG\Plugins\Acf_Location_BlockPageTemplate;
use \WG\Plugins\Acf_Location_HasParent;
use \WG\Plugins\JW_Player;

class Acf {
  protected $json_dir = '/custom-fields/acf-json';
  protected $php_dir = '/custom-fields/acf-json';

  /**
   * Constructor
   */
  function __construct(){
    $this->register_options_pages();

    add_filter( 'acf/settings/load_json', array( $this, 'acf_json_path' ) );
    add_filter( 'acf/settings/save_json', array( $this, 'acf_json_path' ) );
    add_filter( 'acf/settings/show_admin', array( $this, 'show_admin_rule' ), 99 );
    add_filter( 'acf/validate_value/name=main_menu_category_sub_nav_tags', array( $this, 'limit_main_menu_category_sub_nav_tags' ), 10, 4 );
    add_filter( 'acf/load_field/name=ads_position', array( $this, 'acf_load_ad_assignment_options' ) );
    add_filter( 'acf/load_field/name=subpagetype_template', array( $this, 'acf_load_ad_template_options' ) );
    add_filter( 'acf/load_field/name=ads_assignment', array( $this, 'acf_load_ad_assignment_choices' ) );
    add_filter( 'acf/load_field/name=verticals_editorial_tags', array( $this,'acf_load_vertical_choices' ) );
    add_filter( 'acf/load_value/name=infinite_scroll_preset_posts', array( $this,'acf_load_infinite_scroll_choices' ) );
    add_filter( 'acf/load_field/name=infinite_scroll_preset_posts', array( $this,'acf_limit_vertical_count' ) );
    add_filter( 'acf/load_value/name=featured_content_promo_content', array( $this,'acf_load_featured_content_promo_choices' ) );
    add_filter( 'acf/load_field/name=featured_content_promo_content', array( $this,'acf_limit_vertical_count' ) );
    add_filter( 'acf/fields/relationship/query/name=posts_by_term_and_keyword', array( $this, 'rich_tag_acf_relationship_query_tax' ), 10, 3 );
		add_filter( 'acf/fields/relationship/query/key=field_5ed47015b69e7', array( $this, 'rich_tag_acf_relationship_query_tax' ), 10, 3 );
    add_filter( 'acf/load_value/name=verticals_form_assignment', array( $this,'acf_load_verticals_form_choices' ) );
    add_action('acf/init', array( $this, 'init_location_types'));
    // @WORK - this exceeds rate limit of 30 calls/min. we need to create
    //         a button to update these players
    // add_filter( 'acf/load_field/name=jwplayer_default_player', array( $this, 'acf_load_jw_default_player_choices' ) );
    // add_filter( 'acf/load_field/name=post_hero_video_player', array( $this, 'acf_load_jw_hero_players_choices' ) );
    // /WORK
  }

  function init_location_types() {
    if( !function_exists('acf_register_location_type') ) return;
    acf_register_location_type( '\WG\Plugins\Acf_Location_BlockPageTemplate' );
    acf_register_location_type( '\WG\Plugins\Acf_Location_HasParent' );
  }

  function rich_tag_acf_relationship_query_tax($args, $field, $post_id){
		$args['orderby'] = 'date';
    $args['order'] = 'DESC';

    // Posts grid + Video grid
		if(!isset($args['tax_query'])){
			$args['tax_query'] = [
				[
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => str_replace('term_','',$post_id),
				]
			];
    }

    // Video grid only
		if($field['key'] === 'field_5ed47015b69e7') {
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][] = [
				'relation' => 'OR',
				[
					'taxonomy' => 'backend_tag',
					'field'    => 'slug',
					'terms'    => 'video',
				],
				[
					'taxonomy' => 'tag',
					'field'    => 'slug',
					'terms'    => 'video',
				]
			];
		}
		return $args;
	}

  /**
   * Pull ad units from desktop, mobile and
   * AMP units and load into Ad Assignment choice fields
   *
   * @param object $field - field that is being altered
   * @return object $field - altered field
   */
  function acf_load_ad_assignment_choices( $field ) {
    $field['choices'] = array(); // clear current choices

    // Get merge, and map all of the ad units from ACF Ad Options
    $ads_desktop_units = get_field( 'ads_desktop_units', 'options' ) ?? array();
    $ads_mobile_units = get_field( 'ads_mobile_units', 'options' ) ?? array();
    $ads_amp_units = get_field( 'ads_amp_units', 'options' ) ?? array();
    $units = array_map(function( $unit ) {
        return $unit['type-name'];
    }, array_merge(
        $ads_desktop_units,
        $ads_mobile_units,
        $ads_amp_units
    ) );

    // Add choices to the Ad Assignment fields
    foreach($units as $unit) :
        $field['choices'][$unit] = $unit;
    endforeach;

    return $field;
  }

  /**
   * Pull ad assignments from both mobile and
   * desktop units into ad assignment choices
   * assignment to an available ad position
   *
   * @param object $field - field that is being altered
   * @return object $field - altered field
   */
  function acf_load_ad_template_options( $field ) {
    $field['choices'] = array(); // clear current choices

    $templates = wp_get_theme()->get_page_templates();
    foreach( $templates as $template_slug => $template_name ) :
        $field['choices'][$template_slug] = $template_name;
    endforeach;

    return $field;
  }

  /**
   * Pull ad assignments from both mobile and
   * desktop units into ad assignment choices
   * assignment to an available ad position
   *
   * @param object $field - field that is being altered
   * @return object $field - altered field
   */
  function acf_load_ad_assignment_options( $field ) {
    $field['choices'] = array(); // clear current choices

    $positions = get_ad_config();

    foreach($positions as $position => $settings) :
        $field['choices'][$position] = $settings['description'];
    endforeach;

    return $field;
  }

  /**
   * Limit main menu category sub nav tag
   * selection to 6 items
   */
  function limit_main_menu_category_sub_nav_tags( $valid, $value, $field, $input ) {

    if( ! $valid ) :
      return $valid;
    endif;

    if ( sizeof( $value ) > 6 ) :
      $valid = 'You can only select 6 terms';
    else :
      $valid = true;
    endif;

    return $valid;

  }

  /**
   * Only allow super admin ACF rights
   */
  function show_admin_rule() {
    $user = new \WP_User( 49 );
    $user->add_cap( 'field_admin' );
    return current_user_can( 'field_admin' );
  }

  /**
   * ACF options pages
   */
  function register_options_pages() {
    if ( ! function_exists( 'acf_add_options_page' ) ) :
      return;
    endif;

    acf_add_options_page(array(
      'page_title'    => 'Theme Options',
      'menu_title'    => 'Theme Options',
      'menu_slug'     => 'theme-options',
      'capability'    => 'edit_posts',
      'redirect'      => false
    ) );

    acf_add_options_sub_page(array(
      'page_title'    => 'Ad Options',
      'menu_title'    => 'Ad Options',
      'parent_slug'	  => 'theme-options',
    ));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Email Capture Options',
		'menu_title'	=> 'Email Capture Options',
		'parent_slug'	=> 'theme-options',
	  ));

    acf_add_options_sub_page(array(
      'page_title'    => 'Vertical Options',
      'menu_title'    => 'Vertical Options',
      'parent_slug'	  => 'theme-options',
    ));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Commerce Options',
		'menu_title'	=> 'Commerce Options',
		'parent_slug'	=> 'theme-options',
	  ));

    acf_add_options_sub_page(array(
      'page_title'    => 'Third Party Integrations',
      'menu_title'    => 'Third Party Integrations',
      'parent_slug'	  => 'theme-options',
    ));
  }

  /**
   * The path where JSON files are created when ACF field groups are saved/updated
   * @param string path of save point
   * @return string path of save point
   * @link http://www.advancedcustomfields.com/resources/local-json/
   * @link http://www.advancedcustomfields.com/resources/synchronized-json/
   */
  function acf_json_path( $paths ) {
    $acf_json_path = TEMPLATEPATH . $this->json_dir;

    if ( is_array( $paths ) ) :
      unset($paths[0]);
      $acf_json_path = array( $acf_json_path );
    endif;

    return $acf_json_path;
  }


  /**
   * Populate the Verticals ACF settings
   * that exists in Theme Options with all Editorial Tags
   *
   * @param object $field - ACF field object
   * @return object $field - ACF field with new `choices`
   */
  function acf_load_vertical_choices( $field ) {
    $field['choices'] = array(); // clear current choices

    $categories = get_categories(
      array(
        'taxonomy' => 'category',
        'hide_empty' => false
      )
    );

    foreach ( $categories as $category ) :
      $field['choices'][$category->term_id] = $category->name;
    endforeach;

    return $field;
  }


/**
 * Populate the infinite scroll choices field in
 * Theme Options to include all available Verticals
 *
 * @param array $value - list of values for the field
 * @return array $value - updated list of values for the field
 */
function acf_load_verticals_form_choices( $value ) {
  $current_value = $value;
  $current_choices = array_map(
    function( $editorialtag ) {
      return strtolower(
        str_replace( ' ', '_', $editorialtag['verticals_name'] )
      );
    },
    (array) get_field( 'verticals', 'options' )
  );
  $value = array_values(
    array_filter(
      (array) $current_value,
      function( $entry ) use( $current_choices ) {
        return in_array( $entry['field_5f0f6320739ef'], $current_choices );
      }
    )
  );

  foreach( $current_choices as $i => $choice ) :
    foreach( $value as $entry ) :
      if ( $entry['field_5f0f6320739ef'] == $choice ) :
        unset($current_choices[$i]);
      endif;
    endforeach;
  endforeach;

  $new_entries = array_values( $current_choices );
  foreach( $new_entries as $entry ) :
    $value[] = array(
      'field_5f0f6320739ef' => $entry,
      'field_5e32b8996b1de' => NULL
    );
  endforeach;

  return $value;
}

  /**
   * Limit the number of fields allowed in infinite scroll field
   * to the number of Verticals set
   *
   * @param object $field - ACF field object
   * @return object $field - ACF field with new `choices`
   */
  function acf_limit_vertical_count( $field ) {
    $verticals = get_field( 'verticals', 'options' );
    $vertical_count = $verticals ? count( $verticals ) : 0;

    if ( $vertical_count > 0 ) :
      $field['max'] = $vertical_count;
      $field['min'] = $vertical_count;
    else :
      $field['sub_fields'] = array();
      $field['instructions'] = 'Add verticals in the "Verticals" Tab to select posts';
    endif;

    return $field;
  }

/**
 * Populate the featured content promo choices field in
 * Theme Options to include all available Verticals
 *
 * @param array $value - list of values for the field
 * @return array $value - updated list of values for the field
 */
function acf_load_featured_content_promo_choices( $value ) {
  $current_value = $value;
  $current_choices = array_map(
    function( $editorialtag ) {
      return strtolower(
        str_replace( ' ', '_', $editorialtag['verticals_name'] )
      );
    },
    (array) get_field( 'verticals', 'options' )
  );
  $value = array_values(
    array_filter(
      (array) $current_value,
      function( $entry ) use( $current_choices ) {
        return in_array( $entry['field_5f495a8db0153'], $current_choices );
      }
    )
  );

  foreach( $current_choices as $i => $choice ) :
    foreach( $value as $entry ) :
      if ( $entry['field_5f495a8db0153'] == $choice ) :
        unset($current_choices[$i]);
      endif;
    endforeach;
  endforeach;

  $new_entries = array_values( $current_choices );
  foreach( $new_entries as $entry ) :
    $value[] = array(
      'field_5f495a8db0153' => $entry,
      'field_5f495a8db0154' => NULL
    );
  endforeach;

  return $value;
}

/**
 * Populate the infinite scroll choices field in
 * Theme Options to include all available Verticals
 *
 * @param array $value - list of values for the field
 * @return array $value - updated list of values for the field
 */
function acf_load_infinite_scroll_choices( $value ) {
  $current_value = $value;
  $current_choices = array_map(
    function( $editorialtag ) {
      return strtolower(
        str_replace( ' ', '_', $editorialtag['verticals_name'] )
      );
    },
    (array) get_field( 'verticals', 'options' )
  );
  $value = array_values(
    array_filter(
      (array) $current_value,
      function( $entry ) use( $current_choices ) {
        return in_array( $entry['field_5e32b8856b1dd'], $current_choices );
      }
    )
  );

  foreach( $current_choices as $i => $choice ) :
    foreach( $value as $entry ) :
      if ( $entry['field_5e32b8856b1dd'] == $choice ) :
        unset($current_choices[$i]);
      endif;
    endforeach;
  endforeach;

  $new_entries = array_values( $current_choices );
  foreach( $new_entries as $entry ) :
    $value[] = array(
      'field_5e32b8856b1dd' => $entry,
      'field_5e32b8996b1de' => NULL
    );
  endforeach;

  return $value;
}

  function acf_load_jw_default_player_choices( $field ) {
    if ( ( ! JW_Player::$jwplayer_api_key || ! JW_Player::$jwplayer_secret_key ) || ( isset( $_GET['page'] ) && $_GET['page'] !== 'acf-options-third-party-integrations' ) ) :
      return $field;
    endif;

    $field['choices'] = array(); // clear current choices
    $field['choices'] = JW_Player::get_jw_player_players();

    return $field;
  }


  function acf_load_jw_hero_players_choices( $field ) {
    global $pagenow;
    if ( ( ! JW_Player::$jwplayer_api_key || ! JW_Player::$jwplayer_secret_key ) || $pagenow !== 'post.php' ) :
      return $field;
    endif;

    $field['choices'] = array(); // clear current choices
    $field['choices'] = JW_Player::get_jw_player_players();

    return $field;
  }
}
