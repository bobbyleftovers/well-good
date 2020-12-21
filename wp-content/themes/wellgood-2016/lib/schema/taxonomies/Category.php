<?php
/**
 * Category
 *
 * Options and settings for the category taxonomy
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */

namespace WG\Schema\Taxonomies;

class Category {

  static $legacy_categories = array(
    'good-advice',
    'good-food',
    'good-home',
    'good-looks',
    'good-sweat',
    'good-travel'
  );

  static $fallback_legacy_category_map = array(
    'Good Travel' => array(
      'Travel Tips',
      'Places To Visit',
      'Wellness Retreats',
      'Travel Ideas',
      'Abroad Strokes'
    ),
    'Good Looks' => array(
      'Skin Care Tips',
      'Clean Beauty',
      'Hair Care Tips',
      'Hair Style Tips',
      'Nail Care',
      'Makeup Tips',
      'Active Clothing',
      'Winter Skin Care',
      'Summer Skin Care',
      'Natural Acne Treatment',
      'Workout Outfits',
      'Athleisure Wear',
      'Bras and Undies',
      'Size Inclusive Brands',
      'Sustainable Fashion',
      'Sneaker Trends',
      'Jewelry Pieces',
      'Clothing Care',
      'Beauty Geek',
      'Ultimate Guide',
      'Dear Derm',
      'Beauty Week'
    ),
    'Good Sweat' => array(
      'Yoga',
      'Running',
      'Pilates',
      'Barre Workouts',
      'HIIT Training Workouts',
      'Dance Workouts',
      'Crossfit Workouts',
      'Boxing Tips',
      'Cycling Workouts',
      'Water Activities',
      'Active Recovery',
      'Fitness Tips',
      'Fitness Technology',
      'Trainer of the Month Club',
      'The Right Way',
      'Good Moves',
      'Yoga Equipment',
      'Yoga Moves',
      'Yoga Studios Near Me',
      'Marathon Training',
      'Running Routes Near Me',
      'Running Tips',
      'The United States of Running',
      'Pilates Classes',
      'Pilates Workouts',
      'Spinning Workouts',
      'Surfing Tips'
    ),
    'Good Home' => array(
      'Organization Ideas',
      'Decorating Ideas',
      'Cleaning Hacks',
      'Gardening Tips',
      'Home Tech',
      'Home Decor Ideas',
      'Indoor Plant Ideas',
      'DIY Home Decor',
      'Sustainable Living'
    ),
    'Good Advice' => array(
      'Career Advice',
      'Financial Tips',
      'Dating Tips',
      'Relationship Tips',
      'Sex Advice',
      'Healthy Pregnancy',
      'Parenting Advice',
      'Holistic Treatment',
      'Healthy Body',
      'Healthy Mind',
      'Spiritual Health',
      'Women\'s Empowerment',
      'Healthy Gut',
      'Breast Health',
      'Hormone Health',
      'Menstrual Health',
      'Meditation 101',
      'Healthy Sleeping Habits',
      'Self Care Tips',
      'Mental Challenges',
      'Astrology',
      'Good at Work',
      'OK TMI',
      'Checks Balanced',
      'Cosmic Health',
      'Wellness in Color',
      'On the Cusp',
      'Confused About',
      'What The Wellness',
      'Self-care Nation',
      'Wellness Council',
      'Renew Year',
      'Wellness Trends',
      'Political Issues',
      'Well Good Talks'
    ),
    'Good Food' => array(
      'Food And Nutrition',
      'Vitamins and Supplements',
      'Healthy Eating Tips',
      'Healthy Cooking',
      'Healthy Meal Ideas',
      'Healthy Snack Ideas',
      'Healthy Drinks',
      'Healthy Eating Plans',
      'Healthy Recipes For Dinner',
      'Healthy Lunch Recipes',
      'Healthy Breakfast Recipes',
      'Eating Vegan',
      'Eating Vegetarian',
      'Eating Gluten-Free',
      'Eating Paleo',
      'Eating Keto',
      'Cook with Us',
      'Food Diaries',
      'Restaurant Guides',
      'Alt-Baking Boot Camp',
      'You Versus Food',
      'Plant Based'
    )
  );

  function __construct() {
    add_action( 'pre_get_posts', array( $this, 'edit_query_count_in_feed' ) );
    add_action( 'init', array( $this, 'change_cat_taxonomy_to_editorialtag' ) );
    add_action( 'admin_menu', array( $this, 'change_cat_label_to_editorialtags' ) );
    add_filter( 'category_template', array( $this,'load_editorialtag_template' ) );
    add_action( 'save_post', array( $this, 'set_editorialtags' ), 11 );
    add_action( 'save_post', array( $this, 'set_primary_category' ), 12 );
    add_action( 'save_post', array( $this, 'add_notice_if_duplicate' ), 10 );
    add_action( 'edit_post', array( $this, 'add_notice_if_duplicate' ), 10 );
    add_action( 'admin_init', array( &$this, 'load_admin_notices' ) );
    add_filter( 'acf/load_field/name=hero_tag', array( $this, 'acf_load_tag_choices' ) );
    add_filter( 'acf/load_field/name=tag_1', array( $this, 'acf_load_tag_choices'));
    add_filter( 'acf/load_field/name=tag_2', array( $this, 'acf_load_tag_choices'));
    add_filter( 'acf/load_field/key=field_5c3fdbfa81742', array( $this, 'acf_load_tag_choices' ) );

    // Filter terms in feeds
    add_action('pre_get_posts', array($this, 'filter_terms_in_feeds'));
  }

  // Filter terms in feeds
  function filter_terms_in_feeds(){
    if(!is_feed()) return;
    add_filter('get_the_categories', array($this,'filter_legacy_categories'));
  }

  function load_admin_notices() {
    add_action('admin_notices', array(&$this, 'admin_notices_duplicate_post_tag'));
  }

  function admin_notices_duplicate_post_tag() {
    $post_id = !empty($_GET['post']) ? $_GET['post'] : null;
    if ( empty( $post_id ) ) :
      return;
    endif;

    $screen = get_current_screen();
    $is_editing_screen = ($screen->parent_base == 'edit' && $screen->post_type == 'post');
    $is_duplicate_post_slug = !empty(get_transient( "wg_save_post_error_duplicate_{$post_id}" ) );

    if ( $is_editing_screen && $is_duplicate_post_slug ) {
      $class = 'notice notice-error';
      $message = __('There is already an Editorial Category with the same slug as this post. Please change the post slug to avoid conflict!', 'wellgood-2016');
      printf('<div class="%1$s"><p><strong>%2$s</strong> %3$s</p></div>', esc_attr($class), __('Warning:', 'wellgood-2016'), esc_html($message));
    }
  }

  function add_notice_if_duplicate($post_id) {
    $post_obj = get_post($post_id);

    if (empty($post_obj)) {
      return;
    }

    $matched_category = get_category_by_slug($post_obj->post_name);

    // We set transient to call in admin_notices hooks. Direct call admin_notices will not work
    if (!empty($matched_category)) {
      set_transient('wg_save_post_error_duplicate_' . $post_id, $post_obj->post_name, 10 * MINUTE_IN_SECONDS);
    } else {
      delete_transient('wg_save_post_error_duplicate_' . $post_id);
    }
  }

  /**
   * Control the number of posts in any given page
   * Overrides the setting in
   * Settings->Reading->Blog pages show at most
   *
   * @param object $query - wp_query in pre_get_posts
   */
  function edit_query_count_in_feed( $query ) {
    if (is_admin()) return;
    if ( $query->is_category() ) :
      $cat = get_category_by_slug($query->query_vars['category_name']);
      $cat_depth = get_category_depth($cat);
      if ( ! is_admin() && $query->is_main_query() && $cat_depth === 0 ) :
        $query->set( 'posts_per_page', 41 );
      endif;
    endif;
  }

  /**
   * Rename default category taxonomy to Editorial Tags
   */
  function change_cat_label_to_editorialtags() {
    global $submenu;
    $submenu['edit.php'][15][0] = 'Editorial Tags';
  }

  function change_cat_taxonomy_to_editorialtag() {
    global $wp_taxonomies;
    $labels = &$wp_taxonomies['category']->labels;
    $labels->name = 'Editorial Tags';
    $labels->singular_name = 'Editorial Tag';
    $labels->add_new = 'Add Editorial Tag';
    $labels->add_new_item = 'Add Editorial Tag';
    $labels->edit_item = 'Edit Editorial Tag';
    $labels->new_item = 'Editorial Tag';
    $labels->view_item = 'View Editorial Tag';
    $labels->search_items = 'Search Editorial Tag';
    $labels->not_found = 'No Editorial Tags found';
    $labels->not_found_in_trash = 'No Editorial Tags found in Trash';
    $labels->all_items = 'All Editorial Tags';
    $labels->menu_name = 'Editorial Tags';
    $labels->name_admin_bar = 'Editorial Tags';
    $labels->back_to_items = '← Back to Editorial Tags';
  }

  /**
   * Render template based on category depth
   * @param string $template - current template
   * @param string $template - new template
   */
  function load_editorialtag_template($template) {

    $template = 'editorial-tag.php';

    return locate_template($template);
  }

  // @WORK TRANSIT
  static function get_legacy_category( $post_id ) {
    $categories = get_the_category( $post_id );
    $legacy_categories = self::filter_legacy_categories( $categories, FALSE );

    return ( $legacy_categories ) ? $legacy_categories[0] : NULL;
  }
  // /WORK TRANSIT

  /**
   * Convert custom tag fields into categories
   * @param int ID of post from which to retrieve
   * custom tag fields
   */
  function set_editorialtags( $post_id ) {
    $set_tags = array();

    // @WORK TRANSIT
    $legacy_category = self::get_legacy_category( $post_id );
    if ( $legacy_category ) :
      array_push( $set_tags, $legacy_category->term_id );
      wp_set_post_terms( $post_id, 'legacy-category-' . $legacy_category->slug, 'dev_tag', TRUE );

    else:
      $generated_legacy_category = self::generate_legacy_category( $post_id );
      if ( $generated_legacy_category ) :
        array_push( $set_tags, $generated_legacy_category->term_id );
        wp_set_post_terms( $post_id, 'legacy-category-' . $generated_legacy_category->slug, 'dev_tag', TRUE );
      endif;
    endif;
    // /WORK TRANSIT

    $hero_tag = get_field( 'hero_tag', $post_id );
    if ( $hero_tag ) :
      $hero_tag = is_array( $hero_tag ) ? $hero_tag[0] : $hero_tag;
      array_push( $set_tags, $hero_tag );
    endif;

    foreach ( $set_tags as $tag ) :
      $tag_object = get_term( $tag, 'category') ;
      if ( $tag_object->parent != 0 ) :
        if ( ! in_array( $tag_object->parent, $set_tags ) ) :
          array_push( $set_tags, $tag_object->parent );
        endif;
      endif;
    endforeach;

    $tag_1 = get_field( 'tag_1', $post_id );
    if ( $tag_1 ) :
      $tag_1 = is_array( $tag_1 ) ? $tag_1[0] : $tag_1;
      if ( ! in_array( $tag_1, $set_tags ) ) :
        array_push( $set_tags, $tag_1 );
      endif;
    endif;

    $tag_2 = get_field( 'tag_2', $post_id );
    if ( $tag_2 ) :
      $tag_2 = is_array( $tag_2 ) ? $tag_2[0] : $tag_2;
      if ( ! in_array( $tag_2, $set_tags ) ) :
        array_push( $set_tags, $tag_2 );
      endif;
    endif;

    wp_set_post_categories( $post_id, array_unique( $set_tags ), false );
  }

  // @WORK TRANSIT
  // this function will be deleted in phase 2
  function generate_legacy_category($post_id) {
    $legacy_category = NULL;

    $hero_tag = get_field('hero_tag', $post_id);
    if ($hero_tag) :
      $hero_tag = is_array($hero_tag) ? $hero_tag[0] : $hero_tag;
      $hero_tag_object = get_term($hero_tag, 'category');
      $hero_tag_legacy_category = get_field( 'editorialtag_legacy_category', "category_{$hero_tag_object->term_id}");

      if ( $hero_tag_legacy_category ) :
        $legacy_category = get_term_by('slug', $hero_tag_legacy_category, 'category');
      else :
        foreach(self::$fallback_legacy_category_map as $association => $associations) :
          if (in_array($hero_tag_object->name, $associations)) :
            $legacy_category = get_term_by('name', $association, 'category');
            break;
          endif;
        endforeach;
      endif;
    endif;

    return $legacy_category;
  }
  // /WORK TRANSIT

  function set_primary_category($post_id) {
    // @WORK TRANSIT
    // set the legacy category as primary
    $legacy_category = self::get_legacy_category( $post_id );

    if ( $legacy_category ) :
      if ( class_exists( 'WPSEO_Primary_Term' ) && class_exists( 'WPSEO_Primary_Term_Admin' ) ) :
        $wpseo_primary_term = new \WPSEO_Primary_Term( 'category', $post_id );
        $wpseo_primary_term->set_primary_term( intval( $legacy_category->term_id ) );
        $wpseo_primary_term_admin = new \WPSEO_Primary_Term_Admin();
        $wpseo_primary_term_admin->save_primary_terms( $post_id );
      endif;

      update_post_meta( $post_id, '_yoast_wpseo_primary_category', intval( $legacy_category->term_id ) );
    endif;
    // /WORK TRANSIT

    // @WORK FUTURE
    //// currently we need to set the legacy category
    //// as the primary category. these will continue being
    //// set until we flatten all the permalinks
    // $hero_tag = get_field('hero_tag', $post_id);
    // if ($hero_tag) :
    //   $hero_tag = is_array($hero_tag) ? $hero_tag[0] : $hero_tag;
    //   if (class_exists('WPSEO_Primary_Term') && class_exists('WPSEO_Primary_Term_Admin')) :
    //     $wpseo_primary_term = new \WPSEO_Primary_Term('category', $post_id);
    //     $wpseo_primary_term->set_primary_term(intval($hero_tag));
    //     $wpseo_primary_term_admin = new \WPSEO_Primary_Term_Admin();
    //     $wpseo_primary_term_admin->save_primary_terms($post_id);
    //   endif;

    //   update_post_meta($post_id, '_yoast_wpseo_primary_category', intval($hero_tag));
    // endif;
    // /WORK FUTURE
  }

  /**
   * Pull third-level categories into custom tag
   * field options
   * @param object $field - field that is being altered
   * @return object $field - altered field
   */
  function acf_load_tag_choices( $field ) {
    $field['choices'] = array(); // clear current choices

    $categories = get_categories(
      array(
        'taxonomy' => 'category',
        'hide_empty' => false
        // @WORK TRANSIT
        // We won't need an exclude arg when we get rid
        // of the legacy categories
        ,'exclude' => array_map( function( $legacy_category ) {
          return $legacy_category->term_id;
        }, self::filter_legacy_categories( get_categories(), FALSE ) )
        // /WORK TRANSIT
      )
    );

    foreach ($categories as $category) :
      $field['choices'][$category->term_id] = $category->name;
    endforeach;

    return $field;
  }

  /**
   * @WORK TRANSIT
   * Filter legacy categories
   *
   * By sending a category list, the legacy categories can be
   * either included or excluded
   *
   * @param [array] $categories
   * @param boolean $exclude
   * @return array $filtered_categories
   */
  static function filter_legacy_categories( $categories, $exclude=TRUE ) {
    $filtered_categories = array_values( array_filter( (array) $categories, function( $category ) use( $exclude ) {
      $in_array = in_array( $category->slug, self::$legacy_categories );

      return $exclude ? ! $in_array : $in_array;
    }));
    return $filtered_categories;
  }
  // /WORK TRANSIT

  // get Dev Tag
  static function get_post_dev_tag ($post_id) {
    $tags = get_the_terms($post_id, 'dev_tag');
    if (!is_wp_error($tags) && !empty($tags)) {
      return $tags[0];
    }

    return null;
  }

  // return YOAST primary category
  // marking as depcreciated and creating new version below
  static function get_post_primary_category($post_id, $term='category', $return_all_categories=false){
    $return = array();

    if (class_exists('WPSEO_Primary_Term')){
        // Show Primary category by Yoast if it is enabled & set
        $wpseo_primary_term = new \WPSEO_Primary_Term( $term, $post_id );
        $primary_term = get_term($wpseo_primary_term->get_primary_term());

        if (!is_wp_error($primary_term)){
            $return['primary_category'] = $primary_term;
        }
    }

    if (empty($return['primary_category']) || $return_all_categories){
        $categories_list = get_the_terms($post_id, $term);

        if (empty($return['primary_category']) && !empty($categories_list)){
            $return['primary_category'] = $categories_list[0];  //get the first category
        }
        if ($return_all_categories){
            $return['all_categories'] = array();

            if (!empty($categories_list)){
                foreach($categories_list as &$category){
                    $return['all_categories'][] = $category->term_id;
                }
            }
        }
    }
    return isset($return['primary_category']) ? $return['primary_category'] : null;
  }

  // fetch legacy category slug
  static function get_post_main_category_slug ($post_id) {
    $primary_category = self::get_post_primary_category($post_id);
    if ($primary_category) {
      if ( in_array($primary_category->slug, self::$legacy_categories) ) {
        return $primary_category->slug;
      }
    }

    if ($dev_tag = self::get_post_dev_tag($post_id)) {
      return str_replace('legacy-category-', '', $dev_tag->slug);
    }

    // Fallback to primary category is present
    return $primary_category ? $primary_category->slug : null;
  }
}
