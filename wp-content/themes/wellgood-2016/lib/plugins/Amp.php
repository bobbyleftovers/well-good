<?php
/**
 * Accelerated Mobile Pages
 *
 * Actions and filters for Google AMP page template
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 *
 * @see https://ampforwp.com/tutorials/article/hooks-in-ampforwp/
 */


namespace WG\Plugins;

class Amp {

  /**
   * Constructor
   */
  function __construct() {
    add_action('amp_post_template_footer', array($this,'post_template_footer'));
    add_filter( 'the_content', array($this,'fix_amp_space') );
    add_filter( 'the_content', array($this,'add_amp_jwplayer') );
    add_action( 'amp_post_template_css', array($this,'amp_custom_styles') );
    add_action( 'amp_post_template_footer', array($this,'on_amp_footer') );
    add_action( 'amp_post_template_head', array($this,'on_amp_header') );
    add_filter( 'amp_post_template_data', array($this,'wellgood_amp_component_scripts') );
    add_filter( 'amp_post_template_analytics', array($this,'add_custom_amp_codes') );
    add_filter( 'the_content', array($this,'add_header_slideshow') );
    // Fix AMP plugin URL rewrite to work wtih our permalink structure
    add_action('init', function() {
      add_rewrite_endpoint( 'amp', EP_PERMALINK | EP_PAGES );
    });
  }

  function add_header_slideshow( $content ) {
    if ( !is_amp_endpoint() ) :
      return $content;
    endif;

    global $post;

    $output = '';

    if ( get_field( 'enable_slideshow', $post ) == true ) :
      $output .= get_module('slideshow', get_field( 'slideshow', $post ) );
    endif;

    $output .= $content;

    return $output;
  }


  // @ZALO @KARAN - is this needed anymore?
  static function filter_amp_article_content( $content = '' ) {

    $doc = new \DOMDocument;
    libxml_use_internal_errors(true);
    $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();
    $doc = self::center_amp_aligncenter_imgs($doc);

    return $doc->saveHTML();

  }

  static function center_amp_aligncenter_imgs($doc) {

    $pTags = $doc->getElementsByTagName('p');

    foreach ($pTags as $p) {
      $imgs = $p->getElementsByTagName('img');

      if(
        $imgs->item(0) &&
        $imgs->item(0)->hasAttribute('class') &&
        strpos($imgs->item(0)->getAttribute('class'), 'aligncenter') == true
      ) {
        $p->setAttribute('style', 'text-align: center');
      }
    }

    return $doc;
  }

/**
 * Add necessary scripts for AMP articles
 */
function add_custom_amp_codes( $analytics, $type = null ) {
  if( !array($analytics) ){
    $analytics = array();
  }

  // Comscore
  $analytics['comscore'] = array(
    'type' => 'comscore',
    'attributes' => array(),
    'config_data' => array(
      'vars' => array(
        'c2' => '19765212'
      ),
      'extraUrlParams' => array(
        'comscorekw' => 'amp'
        )
        )
      );

  // Google Tag Manager -- GTM
  $dataLayer = get_datalayer_data(get_the_id());
  $analytics['gtm'] = array(
    'type' => '',
    'attributes' => array(
      'config' => 'https://www.googletagmanager.com/amp.json?id=GTM-T29C67P&gtm.url=SOURCE_URL',
      'data-credentials' => 'include'
    ),
    'config_data' => array(
      'vars' => $dataLayer
    )
  );

  if ( "live" === @$_SERVER['PANTHEON_ENVIRONMENT'] ){
    // $ga_property = get_field('ga_property_tracking_code', 'options');
    // $analytics['googleanalytics'] = array(
    //   'type' => 'googleanalytics',
    //   'attributes' => array(),
    //   'config_data' => array(
    //     'vars' => array('account' => $ga_property),
    //     'triggers' => array(
    //       'trackPageview' => array(
    //         'on' => 'visible',
    //         'request' => 'pageview'
    //       )
    //     )
    //   )
    // );

    // Parsely
    $analytics['parsely'] = array(
      'type' => 'parsely',
      'attributes' => array(),
      'config_data' => array(
        'vars' => array(
          'apikey' => 'wellandgood.com'
        )
      )
    );
  }
  // Simplereach
  if( is_single() ) {
    global $post;

    $simpleReachEnabled	= get_field('enable_simplereach_tracking');

    if( $simpleReachEnabled ) {
      $pid_code = get_field('simplereach_pid_code', 'options');
      $title = $post->post_title;
      $categories = get_the_category();
      $tags = get_the_tags();
      $date = get_the_date();
      $sr_cats = array();
      $sr_tags = array();
      $authors = array();

      foreach( $categories as $category ){
        array_push($sr_cats, $category->name);
      }
      foreach( $tags as $tag ){
        array_push($sr_tags, $tag->name);
      }

      $authors[] = get_the_author_meta('display_name', $post->post_author);

      $analytics['simplereach'] = array(
        'type' => 'simplereach',
        'attributes' => array(),
        'config_data' => array(
          'vars' => array(
            'pid' => $pid_code,
            'title' => $title,
            'authors' => $authors,
            'categories' => $sr_cats,
            'tags' => $sr_tags,
            'published_at' => $date
          )
        )
      );

    }

  }

  return $analytics;
}

  /**
   * filter: the_content
   */
  function fix_amp_space( $content ) {
      if ( is_amp_endpoint() ) {
        $content = str_replace('data:image/png; base64,', 'data:image/png;base64,', $content);
        return str_replace('data:image/svg+xml; base64,', 'data:image/svg+xml;base64,', $content);
      }
      return $content;
  }

  /**
   * Parse JW Player shortcode as AMP markup
   */
  function parse_jw_shortcode($matches) {
    if (!isset($matches[3]) || empty($matches[3])) {
      return;
    }

    $jw_code = $matches[3];
    $jw_ids = explode('-', $jw_code);

    if (!isset($jw_ids[0]) || !isset($jw_ids[1])) {
      return;
    }

    $player_id = trim($jw_ids[1]);
    $media_id = trim($jw_ids[0]);

    $jw_amp_markup = '<amp-jwplayer
      data-player-id="' . $player_id .'"
      data-media-id="' . $media_id .'"
      layout="responsive"
      width="16"
      height="9"
    ></amp-jwplayer>';

    return $jw_amp_markup;
  }

  /**
   * Add AMP JW Player to content
   *
   * filter: the_content
   */
  function add_amp_jwplayer($content){
    if ( !is_amp_endpoint() ) {
      return $content;
    }

    $jw_shortcode_regex = '/(.?)\[(jwplayer|jwplatform)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s';

    $content = preg_replace_callback($jw_shortcode_regex, array($this, 'parse_jw_shortcode'), $content);

    return $content;
  }

  /*
  *
  *  Head
  *
  */
  function post_template_head(){
    // include_partial('header/amp-head');
  }


  /*
  *
  *  Body Footer
  *
  */
  function post_template_footer(){
    ?>
      <amp-skimlinks layout="nodisplay" publisher-code="104860X1561639"></amp-skimlinks>
    <?php
  }

  /**
   * A function used to hook into the AMP Plugin as per Automaticc docs.
   * This is to stylize the pages for Google AMP.
   */
  function amp_custom_styles( $amp_template ) {
    // only CSS here please...
    include get_template_directory().'/src/css/amp/amp.css.php';
  }

  function on_amp_footer( $amp_template ) {
    $id = $amp_template->post->ID;

    $disclaimer_tax = get_the_terms($id, 'disclaimer');
    $disclaimers =  $disclaimer_tax ? array_map(function($disclaimer) {
      $term_tax = $disclaimer->taxonomy;
      $term_id = $disclaimer->term_id;

      return array(
        'position' => get_field('disclaimer_position', "{$term_tax}_{$term_id}"),
        'text' => get_field('disclaimer_text', "{$term_tax}_{$term_id}")
      );
    }, $disclaimer_tax) : array();
    if ($disclaimers) :
      echo '<div class="amp-wp-disclaimer">' . get_module('disclaimer', array(
        'position' => 'after_content',
        'texts' => $disclaimers
      )) . '</div>';
    endif;

    /**
     * New Relic scripts are being automatically injected from Pantheon.
     * These scripts will cause AMP pages to fail, as the required markup specs are highly specific.
     * This action hook is to inject content into the AMP header, but we'll use it to remove the new relic scripts.
     */
    if ( function_exists('newrelic_disable_autorum') ) :
      newrelic_disable_autorum();
    endif;
  }

  /**
   * Add meta tag to header on amp pages to de-dupe users that visit both amp and non amp pages.
   */
  function on_amp_header( $amp_template ) {
    echo "<meta name=\"amp-google-client-id-api\" content=\"googleanalytics\"/>";
  }

  function wellgood_amp_component_scripts( $data ) {

    $custom_component_scripts = array(
      'amp-carousel'    => 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js',
      'amp-brightcove'   => 'https://cdn.ampproject.org/v0/amp-brightcove-0.1.js',
      'amp-ad'  => 'https://cdn.ampproject.org/v0/amp-ad-0.1.js',
      'amp-iframe' => 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js',
      'amp-bind' => 'https://cdn.ampproject.org/v0/amp-bind-0.1.js',
      'amp-list' => 'https://cdn.ampproject.org/v0/amp-list-0.1.js',
      'amp-skimlinks' => 'https://cdn.ampproject.org/v0/amp-skimlinks-0.1.js',
      'amp-jwplayer' => 'https://cdn.ampproject.org/v0/amp-jwplayer-0.1.js',
      'amp-youtube' => 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js'
    );
    $data['amp_component_scripts'] = array_merge( $data['amp_component_scripts'], $custom_component_scripts );
    return $data;
  }
}
