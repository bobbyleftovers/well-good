<?php


/**
 * Autoload
 */
include_once( __DIR__ . '/autoload.php' );

/**
 * Define constants
 */
define('REST_NAMESPACE', 'wellandgood');
define('REST_VERSION', '1');


/**
 * Namespaces
 */
use WG\Settings;
use WG\Schema\Cpt;
use WG\Schema\Taxonomies;
use WG\API\REST;
use WG\API\Hooks;
use WG\API\Shortcodes;
use WG\Plugins;
use WG\Tools;
use WG\Content;
use WG\Services;
use WG\Meta;

/**
 * DON'T MOVE THIS LINES
 * Needs to be initialized first
 */
new Plugins\Acf();

/**
 * Theme Setup
 */
new Settings\Theme();
new Settings\Scripts();
new Settings\Admin();
// @WORK on hold until we can more clearly plan out the delegation of capabilities
// new Settings\Users();
new Settings\Media();
new Settings\Menus();
new Settings\Permalinks();
new Settings\Sitemap();
new Settings\Gutenberg();

/**
 * CPT
 */
new Cpt\Post();
new Cpt\Page();
new Cpt\Mailing_List();
new Cpt\Hub_Locations();
new Cpt\Collections();
new Cpt\Nominees();
new Cpt\Products();
new Cpt\Slideshow();

/**
 * Taxonomies
 */
new Taxonomies\Ad_Cat();
new Taxonomies\Ad_Tag();
new Taxonomies\Backend_Tag();
new Taxonomies\Dev_Tag();
new Taxonomies\Campaigns();
new Taxonomies\Category();
new Taxonomies\Cities();
new Taxonomies\Dietary();
new Taxonomies\Disclaimer();
new Taxonomies\Experts();
new Taxonomies\Location_Types();
new Taxonomies\Partners();
new Taxonomies\Post_Tag();
new Taxonomies\Product_Campaigns();
new Taxonomies\Product_Categories();
new Taxonomies\Experts();
new Taxonomies\Verticals();


/**
 * REST API
 */
new REST\Hub_Locations();
new REST\Infinite_Scroll();
new REST\Nominees();
new REST\Products();
new REST\Summer_Posts();
new REST\Acf();
new REST\Transfer_To_Backend_Tags();
new REST\Generate_Legacy_Category_Dev_Tags();
new REST\Trending_Articles();
new REST\Parsley();
new REST\Instagram();


/**
 * Shortcodes
 */
new Shortcodes\Shoplinks();
new Shortcodes\Tip();
new Shortcodes\In_Post_Sidebar();
new Shortcodes\Hotspot_Content();
new Shortcodes\Gdpr_Form();
new Shortcodes\Snapapp();
new Shortcodes\Cta();
new Shortcodes\Year();
new Shortcodes\More_Reading();
new Shortcodes\Anchor();
new Shortcodes\Slideshow();
new Shortcodes\Location_Hub_Link();
new Shortcodes\Gallery();
new Shortcodes\Form();
new Shortcodes\Wag_Gallery();
new Shortcodes\WG();


/**
 * Custom Hooks
 */
// new Hooks\Save_Post_Async();
new Hooks\Save_Post_Dom_Parser();

/**
 * Content Filters
 */
new Content\Content_Filters();
new Content\Content_Injections();
new Content\Structured_Video_Data();
new Content\Datalayer();
new Content\Amazon_Affiliate_Links_Filter();
new Content\Title();


/**
 * Plugins config
 */
new Plugins\Amp();
new Plugins\Apple_News();
new Plugins\JW_Player();
new Plugins\Instagram();
new Plugins\Imagelinks();
new Plugins\Page_Post_Redirection();
new Plugins\WP_Ultimate_Recipe();
new Plugins\WG_Varnish();


/**
 * Services
 */
new Services\DoubleClick();
new Services\MSN_Email_Automation();
new Services\Rss();
new Services\Parsely();
new Services\Redirect();
new Services\Pantheon();
new Services\Brightcove();
new Services\Opengraph();
new Services\Iterable_Signup();
// new Services\Typekit();
new Services\Rating();
new Services\Instana();
new Services\YoutubeThumbnailFetch();
new Services\Instagram();


/**
 * Tools
 */
// new Tools\Backward_Compatibility();
new Tools\Dev_Tools();


/**
 * Meta
 */
new Meta\Post_Hero_Settings();
