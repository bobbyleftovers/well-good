<?php

namespace WG\Services;

use WG\Schema\Taxonomies\Category;

class DoubleClick {

  function __construct() {

    new \WG\API\REST\DoubleClick();

    add_filter( 'body_class', array($this,'body_class'));
    add_action( 'wp_head', array( $this, 'wp_print_head_scripts' ), 5 );
  }

  /**
   *  wp_print_head_scripts
   */
  function wp_print_head_scripts() {
    ?>
    <!-- Start GPT Async Tag -->
    <script async='async' src='https://securepubads.g.doubleclick.net/tag/js/gpt.js'></script>
    <script>window.googletag = window.googletag || {cmd: []};</script>
    <style type="text/css">section.advertisement div,section.advertisement iframe{max-width:none!important;}</style>
    <?= get_advertising_sra(get_the_ID(), 0); ?>
    <!-- End GPT Async Tag -->
    <?php
  }

  function body_class( $classes ) {
    if ( $this->has_leaderboard_ad() ) {
      if ( !is_front_page() ){
        $classes[] = 'has-leaderboard-ad';
      }

      if ( is_singular( 'post' ) ) {
        $classes[] = 'has-scrolling-header';
      }
    }
    return $classes;
  }

  function has_leaderboard_ad() {
    //  if ( is_singular( 'post' )
    //         || is_category()
    //         || is_front_page()
    //         || is_404() ) {
    //  	return true;
  }

  static function get_ad_config($json_decode = true) {
    $ad_config = file_get_contents(TEMPLATEPATH . '/ads.json');

    if ($json_decode) :
      return json_decode($ad_config, true);
    else :
      return $ad_config;
    endif;
  }

  static function get_ad_unit($slot, $page, $iteration) {
    $slot = self::get_ad_slot($slot);
    $type = get_field('ads_' . $slot['type'] . '_units', 'options');
    $units = null;

    if (!$type) :
      return;
    endif;

    foreach ($type as $key => $val) :
        if ($val['type-name'] === $slot['assignment']) :
            $units = $type[$key]['type'];
        endif;
    endforeach;

    $unit = $units[$page]['page'][$iteration]['unit'];

    return $unit;
  }


  /**
   * This function will return the <script> tag for all ad slots associated to a Post ID.
   * This is intended to leverage SRA of GPT. tldr; One single request for all ad slots
   * instead of a request per slot.
   *
   * @param INT $post_id = the ID of the post to fetch ads for
   * @param INT $iteration = if we're using infinite scroll, this number is the index position of the article
   *
   * @see https://support.google.com/admanager/answer/183282
   */

  static function get_advertising_sra($post_id, $page) {
    $ad_slots = self::get_ad_slot_definition($page);
    ?>

    <script async type="text/javascript">
      function createUUID() {
        var pow = Math.pow(10, 10);
        var uuid = Math.floor(Math.random() * pow) + '.' + Math.floor(Math.random() * pow);
        return uuid;
      };
      function findPPID() {
        if (!localStorage.getItem('ppid')) {
          ppid = createUUID();
          localStorage.setItem('ppid', ppid);
          return ppid;
        } else {
          return localStorage.getItem('ppid');
        }
      };
      var ppid = findPPID() || '';

      var adSlots = <?= json_encode($ad_slots); ?>;
      googletag.cmd.push(function() {
        try {
          ADSREADY = false;
          permutive.readyWithTimeout(function() {
            var page = null;
            var size = ISMOBILE ? 'mobile' : 'desktop';
            var body = document.querySelector('body')
            var bodyClasses = body ? body.className.split(' ') : [];

            // Filter ads
            ADCODES = Object.keys(adSlots).reduce(function (filtered, key) {
              var isSizeAvailable = ADCONFIG[adSlots[key].type].size.includes(size);
              if (!isSizeAvailable) {
                return filtered;
              }

              var container = ADCONFIG[adSlots[key].type].hasOwnProperty('parent') ? document.querySelectorAll(ADCONFIG[adSlots[key].type].parent)[<?= $page; ?>] : document.querySelector('body');
              var adContainers = container ? container.querySelectorAll(ADCONFIG[adSlots[key].type].container) : [];
              var isIterationAvailable = adContainers.length > 0 ? adSlots[key].iteration < adContainers.length : false;

              if (isSizeAvailable && isIterationAvailable) {
                filtered[key] = eval(adSlots[key].unit);
              }

              return filtered;
            }, {});

            // Test mode
            <?php if ( get_field( 'advertising_test_mode', 'options' ) ) : ?>
              googletag.pubads().setTargeting('test', ['on']);
            <?php endif; ?>

            if (!EMAIL_CAPTURE_INIT) {
              EMAIL_CAPTURE_INIT = true;
              if (ISMOBILE) {
                googletag.defineSlot('/6117/wellgood.mw/emailslider1-1', [1, 3], 'div-gpt-ad-1597160603724-0').addService(googletag.pubads());
              } else {
                googletag.defineSlot('/6117/wellgood/emailslider1-1', [1, 3], 'div-gpt-ad-1597160477614-0').addService(googletag.pubads());
              }
            }

            // setTargeting
            googletag.pubads()
            .setTargeting('lg_uuid', ppid)
            <?php if ($targets = self::get_ad_targeting($post_id ? $post_id : false)) :
              echo "\n$targets";
            endif; ?>

            googletag.pubads().enableSingleRequest();
            // googletag.pubads().collapseEmptyDivs();
            googletag.pubads().enableLazyLoad({
              fetchMarginPercent: 100,
              renderMarginPercent: 50,
              mobileScaling: 1.0
            });
            googletag.enableServices();
            ADSREADY = true;
          }, 'realtime', 1500);
        } catch (e) {};
      });
    </script>
  <?php
  }


  /**
   * Gets ad slot codes from relevant ACF fields
   *
   * @param int $ajax_post_id - the specific post ID to be used when we're not inside the query
   * @return array $ad_codes['slots'] - the ad slots to be rendered in page. This should be output in a single script tag
   */
  static function get_ad_slot_definition($page) {
    $ad_config = file_get_contents( TEMPLATEPATH . '/ads.json' );
    $ad_types = json_decode( $ad_config, true );
    $units = array();

    $desktop_units = get_field( 'ads_desktop_units', 'options' ) ?? array();
    $mobile_units = get_field( 'ads_mobile_units', 'options' ) ?? array();
    foreach( array_merge( $desktop_units, $mobile_units ) as $type ) :
      if ( ! is_array( $type )
        || ! array_key_exists( 'type-name', $type )
        || ! array_key_exists( $type['type-name'], $ad_types )
        || ! array_key_exists( 'type', $type )
        || ! isset( $type['type'][$page] )
        || ! is_array( $type['type'][$page] )
        || ! array_key_exists( 'page', $type['type'][$page] ) ) :
        continue;
      endif;

      foreach( $type['type'][$page]['page'] as $i => $unit ) :
        if ( ! is_array( $unit ) || ! array_key_exists( 'unit', $unit ) ) :
          continue;
        endif;

        $unit_id = self::get_ad_id( $unit['unit'] );
        $units[$unit_id] = array(
          'unit' => $unit['unit'],
          'type' => $type['type-name'],
          'size' => $ad_types[$type['type-name']]['type'],
          'iteration' => $i
        );

      endforeach;
    endforeach;

    return $units;
  }

  /**
   * Deduce an advertisements ID via a regex pattern
   *
   * @param string $ad_string - the javascript code that sets up an ad-slot call
   * @return string - the Ad ID string
   */
  static function get_ad_id($ad_string) {
    preg_match("/div-gpt-ad-[^']*/", $ad_string, $matches);
    $ad_id = $matches && $matches[0] ? $matches[0] : false;

    return $ad_id;
  }

  /**
   * Each ad request that goes out to DFP has some targeting params attached to it, allowing W+G to control
   * the ads that serve for specific articles.
   * Ads on articles are targeted by the article id and the tags associated to a post
   * Ads anywhere else on the site are targeted by the post id.
   * Articles also use some values from within a 3rd party tag, Krux. To add those parameters, we need to wait for it to load.
   *
   * TESTING:
   * Look at your network tab in chrome's devtools and filter requests by "gampad" to see all advertisement requests
   *
   * @param int|null $post_id - the post ID to evaluate against. This is necessary to retain context for any
   * posts that are ajaxed (infinite scroll). Without $post_id, any tempalte tags (is_single()) in ajaxed posts
   * will fail
   */
  static function get_ad_targeting( $post_id = null ) {
    global $post;
    $tags = array(
      'adcat1' => self::get_post_targeting_adcats( $post_id, 0 ),
      'adcat2' => self::get_post_targeting_adcats( $post_id, 1 ),
      'adcat3' => self::get_post_targeting_adcats( $post_id, 2 ),
      'adcat4' => self::get_post_targeting_adcats( $post_id, 3 ),
      'category' => self::get_post_targeting_categories( $post_id ),
      'tags' => self::get_post_targeting_tags( $post_id ),
      'vertical' => self::get_post_targeting_vertical( $post_id ),
      'article_id'  => self::get_post_targeting_id( $post_id ),
      'pagetype' => self::get_post_targeting_page_type( $post_id ),
      'subpagetype' => self::get_post_targeting_subpagetype( $post_id )
    );
    $targeting = array();
    foreach ( $tags as $key => $value ) :
      if ( ! empty( $value ) ) :
        $targeting[] = ".setTargeting('$key', $value)\n";
      endif;
    endforeach;
    return implode($targeting);
  }

  /**
   * Get the post id for targeting purposes.
   * This is traditionally a straight-forward task, but the original function has some logic around
   * deducing the post ID for ads, so I preserved that logic in this function to be safe.
   *
   * @param int $post_id - the ID to check against - this is needed for infinite scroll
   * @return string|null - the ID of the post to target ads in an javascript array format
   */
  static function get_post_targeting_id($post_id = null) {
    global $post;

    if (!$post_id && $post && is_object($post)) :
      $post_id = $post->ID;
    endif;

    if (is_home() || is_front_page() || is_category()) :
      return;
    endif;

    if (is_numeric($post_id)) :
      if (is_page() || is_single() || !empty( $post_id)) :
        return json_encode(strval($post_id));
      endif;
    endif;

    return;
  }

  /**
   * Gets the tags for the article - from the tag,
   * ad tag and backend-tag taxonomies
   *
   * @param int $post_id - the post ID to use, if provided. Need this for any infinite scroll articles
   * @return string|null $value - the string of tags on the article, in the format of a javascript array.
   */
  static function get_post_targeting_tags($post_id = null) {
    global $post;
    $post_id = $post_id ?? $post->ID;
    if ( is_page( $post_id ) || is_category( $post_id ) ) :
      return;
    endif;

    $hero_tag = get_field( 'hero_tag', $post_id );
    $categories = wp_get_post_terms( $post_id, 'category' );
    $non_hero_editorial_tags = array_values( array_filter( array_map( function( $category ) use( $hero_tag ) {
      $term_id = $category->term_id;
      $not_hero = $term_id != intval( $hero_tag );

      return $not_hero ? $term_id : NULL;
    }, Category::filter_legacy_categories( $categories ) ), function( $category ) {
      return $category != NULL;
    } ) );

    $values = array_unique( array_map( function( $value ) {
      return str_replace( '-', '_', $value->slug );
    }, array_merge(
      wp_get_post_terms( $post_id, array(
        'backend_tag',
        'ad_tag'
      ) ),
      array_values( array_filter( array_map( function( $editorial_tag ) {
        if ( isset( $editorial_tag ) ) :
          $object = get_term( $editorial_tag, 'category' );
          $formatted_editorial_tag = $object ? $object : NULL;
        else :
          $formatted_editorial_tag = NULL;
        endif;

        return $formatted_editorial_tag;
      }, $non_hero_editorial_tags ), function( $editorial_tag ) {
        $exists = ! empty( $editorial_tag );

        return $exists;
      } ) )
    ) ) );

    return json_encode($values);
  }

  /**
   * Gets the categories for the article - both from
   * the category and ad category taxonomies
   *
   * @param int $post_id - the post ID to use, if provided. Need this for any infinite scroll articles
   * @return string|null $value - the string of tags on the article, in the format of a javascript array.
   */
  static function get_post_targeting_categories( $post_id = null ) {
    global $post;
    $post_id = $post_id ?? $post->ID;
    if ( is_page( $post_id ) || is_tag( $post_id ) ) :
      return;
    endif;

    if ( ! is_category( $post_id ) ) :
      $categories = wp_get_post_terms( $post_id, 'category' );
      $legacy_category = Category::filter_legacy_categories( $categories, FALSE );
      $hero_tag = get_field( 'hero_tag', $post_id );
      $values = array_values( array_filter( array_map( function( $category ) {
        if ( $category ) :
          $object = get_term( $category, 'category' );
          $formatted_category = str_replace( '-', '_', $object->slug );
        else :
          $formatted_category = NULL;
        endif;

        return $formatted_category;
      }, array(
        count( $legacy_category ) ? $legacy_category[0]->term_id : NULL,
        $hero_tag
      ) ), function( $category ) {
        return $category != NULL;
      } ) );
    else :
      $values = get_queried_object()->slug;
      $values = array( str_replace( '-', '_', $values ) );
    endif;

    $value = json_encode( $values );

    return $value;
  }

  /**
   * Get vertical for post
   *
   * @param [integer] $post_id
   * @return string|null $value - string representation of Vertical.
   */
  static function get_post_targeting_vertical( $post_id = null ) {
    global $post;
    $post_id = $post_id ?? $post->ID;
    if ( is_page( $post_id ) || is_tag( $post_id ) ) :
      return;
    endif;

    if ( ! is_category( $post_id ) ) :
      $category = get_field( 'hero_tag', $post_id );
    else :
      $category = get_queried_object()->id;
    endif;

    $vertical = get_vertical( $category );

    return isset( $category ) && ! empty( $vertical ) ? json_encode( $vertical ) : "";
  }


  static function get_post_targeting_adcats($post_id = null, $index) {
    $adcats = get_data_adcat($post_id);

    return array_key_exists($index, $adcats) ? json_encode($adcats[$index]) : "";
  }


  static function get_post_targeting_page_type($post_id = null) {
    $page_type = str_replace( '-', '_', get_page_type( $post_id, array( 'convention' => 'advertisement' ) ) );

    return json_encode($page_type);
  }


  static function get_post_targeting_subpagetype($post_id = null) {
    $template_slug = get_page_template_slug($post_id);
    $subpagetype_options = get_field('ads_subpagetypes', 'options');

    $type = '';
    if($subpagetype_options){
      foreach($subpagetype_options as $option) :
        if ($template_slug == $option['subpagetype_template']) :
          $type = json_encode($option['subpagetype_name']);
          break;
        endif;
      endforeach;
    }

    return $type;
  }

  static function get_ad_slot($name) {
		$assignments = get_field('ads_assignments', 'options');
		$positions = self::get_ad_config();
		$slot = null;
		if (!$assignments) {
			return;
		}

		foreach ($assignments as $k => $a) :
			if ($a['ads_position'] === $name) :
				$slot = array(
					'assignment' => $assignments[$k]['ads_assignment'],
					'type' => $positions[$name]['type']
				);
			endif;
		endforeach;

		return $slot;
	}
}
