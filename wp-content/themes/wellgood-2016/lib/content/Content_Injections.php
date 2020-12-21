<?php
/**
 * Content Injections
 *
 * This file controls the injection of Advertisements
 * and other media within `the_content` of a post
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */


namespace WG\Content;
use PHPHtmlParser\Dom;

use WG\Services\DoubleClick;

class Content_Injections {

  function __construct() {
    add_filter('the_content', array($this,'content_injections'), 9998, 1);
  }

  /**
   * Check which injection slots are not being used
   * @param array $configured_injections - array of injection
   * settings
   * @param array $injection_slots - array available slots
   * to be injected into
   * @return array $unused_slots - array of index references for
   * unused slots in the $configured_injections
   */
  static function get_unused_slots($configured_injections, $injection_slots) {
    $used_slots = array_values(
      array_filter(
        array_map(
          function($injection) {
            if ($injection) :
              return $injection['target_slot']['index'];
            endif;
          }, $configured_injections
        ),
        function($item) {
          return $item !== null;
        }
      )
    );

    $all_slots = array_map(function($slot) {
      return $slot['index'];
    }, $injection_slots);

    $unused_slots = array_merge(array_diff($used_slots, $all_slots), array_diff($all_slots, $used_slots));

    return $unused_slots;
  }

  /**
   * Add injection to configured injections
   * @param array $configured_injections - array of injection
   * settings
   * @param int $index - index target for new injection
   * @return array $configured_injections - altered array of injection
   * settings
   */
  static function add_injection($configured_injections, $index, $new_injection) {
    if (!array_key_exists($index, $configured_injections)) throw new \Exception("Index not found");

    $temp_array = array();
    $original_index = 0;
    foreach ($configured_injections as $key => $value) :
      if ($key === $index) :
        $temp_array[] = $new_injection;
        break;
      endif;
      $temp_array[$key] = $value;
      $original_index++;
    endforeach;
    array_splice($configured_injections, 0, $original_index, $temp_array);

    return $configured_injections;
  }

  /**
   * Make sure there the number of injections
   * do not exceed the amount of injection
   * slots available or the number of
   * max injections
   * @param array $injections The desired injections
   * @param int $slot_count Number of injection slots available
   * @param int $purge Whether or not to override the injection priority
   * @return array $injections The purged injections
   */
  static function purge_injections($injections, $slot_count, $purge) {
    foreach (array_reverse($injections, true) as $i => $injection) :
      if (!$purge) :
        if (!$injection['required']) :
          unset($injections[$i]);
        endif;
      else :
        unset($injections[$i]);
      endif;
      if (count($injections) <= $slot_count) break;
    endforeach;

    return array_values($injections);
  }


  /**
   * Check if a given slot of content is available to
   * house an injection
   * @param int $i - index of current node
   * @param object $previous_node - previous node
   * @param object $next_node - next nodes
   * @return bool $slot_available - whether or not a slot
   * can be injected into
   */
  static function check_slot($i, $previous_node, $next_node) {
    $slot_available = true;
    $config = array(
      'before' => array(
        'p'
      ),
      'after' => array(
        'p',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6'
      ),
      'min_length' => 200
    );

    // make sure there are nodes before and after
    if (!$previous_node || !$next_node) :
      return false;
    endif;

    // make sure the previous node is in the allowed 'before' tags
    if (!in_array($previous_node->nodeName, $config['before'])) :
      $slot_available = false;
    endif;

    // make sure the previous node is long enough
    if ($previous_node->nodeName == 'p' && strlen($previous_node->nodeValue) < $config['min_length']) :
      $slot_available = false;
    endif;

    // make sure the next node is in the allowed 'after' tags
    if (!in_array($next_node->nodeName, $config['after'])) :
      $slot_available = false;
    endif;

    // make sure the next node is not a space
    if ($next_node->nodeValue == "\xC2\xA0") :
      $slot_available = false;
    endif;

    return $slot_available;
  }


  /**
   * Calculate distance value for each node
   * @param object $node - current node being evaluated
   * @return int $count - distance value
   */
  static function get_node_distance($node) {
    switch (true) :
      case ($node->nodeName == 'p') :
        $count = strlen($node->textContent);
        break;
      case ($node->nodeName == 'figure') :
        $count = 270;
        break;
      default :
        $count = 0;
    endswitch;

    return $count;
  }


  /**
   * Set up configurations for injection logic
   * @param array $injections - array of items to be injected
   * @param array $injection_slots - array available slots
   * to be injected into
   * @return array $configured_injections - array of injection
   * settings
   */
  static function injection_config($injections, $injection_slots) {
    $injection_count = count($injections);
    $slot_count = count($injection_slots);
    $config = array(
      'max_injections' => 8,
      'distance' => 2200
    );

    // purge injections
    $purge = 0;
    while ($injection_count > $slot_count || $injection_count > $config['max_injections']) :
      $injections = self::purge_injections($injections, $slot_count, $purge);
      $injection_count = count($injections);
      $purge++;
    endwhile;

    // layout injections
    $current_slot = 0;
    $last_slot = -1;
    $current_count = 0;
    $configured_injections = array();
    foreach ($injections as $i => $injection) :
      if ($i != 0) :
        while (($current_count + $config['distance']) > $injection_slots[$current_slot]['char_count']) :
          if ($current_slot+1 < $slot_count) :
            $current_slot++;
          else: break;
          endif;
        endwhile;
      else :
        $current_slot = 0;
      endif;
      $target_slot = $injection_slots[$current_slot];

      if ($last_slot < 0 || $last_slot != $current_slot) :
        $configured_injections[] = array(
          'target_slot' => $target_slot,
          'injected' => false
        );
      endif;

      $current_count = $injection_slots[$current_slot]['char_count'];
      $last_slot = $current_slot;
      if ($current_slot+1 < $slot_count) :
        $current_slot++;
      else: continue;
      endif;
    endforeach;

    // add missing injections
    $required_injections = array_filter($injections, function($injection) {
      return $injection['required'] == true;
    });
    $missing_injections = count($required_injections) - count($configured_injections);
    if ($missing_injections > 0) :
      $unused_slots = self::get_unused_slots($configured_injections, $injection_slots);
      for ($x = 0; $x < $missing_injections; $x++) :
        $unused_slot = array_splice($unused_slots,0,1)[0];
        $target_addition = null;
        foreach ($configured_injections as $i => $injection) :
          if ($unused_slot < $injection['target_slot']['index'] && $injection['target_slot']['index'] != 0) :
            $target_addition = $i;
            break;
          endif;
        endforeach;

        $configured_injections = self::add_injection($configured_injections, $target_addition, array(
          'target_slot' => $injection_slots[$target_addition],
          'injected' => false
        ));
      endfor;
    endif;

    // add injection name & props
    foreach (array_keys($configured_injections) as $i) :
      $configured_injections[$i]['name'] = $injections[$i]['name'];
      $configured_injections[$i]['props'] = $injections[$i];

      if (array_key_exists('iteration', $injections[$i])) :
        $configured_injections[$i]['iteration'] = $injections[$i]['iteration'];
      endif;
    endforeach;

    return $configured_injections;
  }

  /*
  * Append HTML
  *
  * https://stackoverflow.com/questions/4400980/how-to-insert-html-to-php-domnode
  *
  * $elem = $dom->createElement('div');
  * appendHTML($elem, '<h1>Hello world</h1>');
  */

  static function appendHTML(\DOMNode $parent, $html) {
    if ( ! $html || empty( trim(strip_tags($html)) ) ) :
      return;
    endif;

    $tmpDoc = new \DOMDocument();
    $tmpDoc->loadHTML($html);

    if ( $tmpDoc->getElementsByTagName('body')->item(0) ) :
      foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
        $node = $parent->ownerDocument->importNode($node, true);
        $parent->appendChild($node);
      }
    endif;
  }

  /**
   * Create injection content
   *
   * @param int $id - post id
   * @param object $doc - domdocument
   * @param string $name - name of injection
   *
   * @return object $injection - domdoc object for injection
   */
  static function create_injection( $doc, $name, $data, $props = null ) {
    $id = array_key_exists('id', $data) ? $data['id'] : 0;
    $page = array_key_exists('page', $data) ? $data['page'] : 0;
    $iteration = array_key_exists('iteration', $data) ? $data['iteration'] : 0;

    switch ( $name ) :
      case 'advertisement' :
        $injection = $doc->createElement('div');
        $injection->setAttribute('class', 'container__ad container__ad--inline container__ad--slot');
        $injection->setAttribute('data-ad-page', $page);
        $injection->setAttribute('data-ad-iteration', $iteration);
        $injection->setAttribute('data-ad-slots', json_encode(array('inline', 'slot')));
        break;

      case 'nativead' :
        $injection = $doc->createElement('div');
        $injection->setAttribute('class', 'container__ad container__ad--desktopnative container__ad--mobilenative');
        $injection->setAttribute('data-ad-page', $page);
        $injection->setAttribute('data-ad-iteration', $iteration);
        $injection->setAttribute('data-ad-slots', json_encode(array('desktopnative', 'mobilenative')));
        break;

      case 'featured-content-promo' :
        $featured_content_promo = brrl_get_module( 'main-2020/featured-content-promo', array(
          'post_id' => $id
        ) );
        
        if ( $featured_content_promo ) :
          $injection = $doc->createElement('div');
          self::appendHTML( $injection, $featured_content_promo );
        endif;
        break;
        
      case 'outstream' :
        $injection = $doc->createElement('div');
        $injection->setAttribute('class', 'container__ad container__ad--outstream');
        $injection->setAttribute('data-ad-page', $page);
        $injection->setAttribute('data-ad-iteration', $iteration);
        $injection->setAttribute('data-ad-slots', json_encode(array('outstream')));
        break;

      case 'ampad' :
        $hero_tag = get_field( 'hero_tag', $id );
        $data_vertical = get_vertical( $hero_tag );
        $ad_data = array(
          'targeting' => array(
            'article_id' => $id,
            'tags' => json_decode( DoubleClick::get_post_targeting_tags( $id ) )
          )
        );
        if ( $data_vertical ) :
          $ad_data['targeting']['vertical'] = $data_vertical;
        endif;

        $amp_units = get_field('ads_amp_units', 'options') ?? array();
        if (empty($amp_units)) : break; endif;
        if (!array_key_exists($iteration, $amp_units[0]['type'][0]['page'])) break;

        $injection = $doc->createElement('div');
        $injection->setAttribute('class', 'amp-ad');

        $amp_ad = $doc->createElement('amp-ad');
        $amp_ad->setAttribute('width', $amp_units[0]['type'][0]['page'][$iteration]['width']);
        $amp_ad->setAttribute('height', $amp_units[0]['type'][0]['page'][$iteration]['height']);
        $amp_ad->setAttribute('type', 'doubleclick');
        $amp_ad->setAttribute('data-slot', $amp_units[0]['type'][0]['page'][$iteration]['unit']);
        $amp_ad->setAttribute('data-multi-size-validation', 'false');
        $amp_ad->setAttribute('json', json_encode( $ad_data ) );
        $amp_ad->setAttribute('rtc-config', "{\"urls\":[\"https://leafgroup.amp.permutive.com/rtc?type=doubleclick\"]}");

        $injection->appendChild($amp_ad);
        break;

      default:
        if ( $props && $props['placeholder'] ) :
          $injection = self::make_injection_placeholder($doc, $props['name']);
        endif;
        break;

    endswitch;

    return $injection ?? null;
  }

  static function get_content_args($content) {
    $data_preg = '/<!-- ?data-[a-zA-Z0-9]+: ?(\d+) ?-->/';
    preg_match($data_preg, $content, $data_args);

    if ($data_args) :
      $content = preg_replace($data_preg, '', $content);
      $instance = $data_args[1];
    else :
      $instance = 0;
    endif;

    return array(
      $content,
      $instance
    );
  }

  static function get_injections($nodes, $id, $content_type = 'standard') {
    $injections = array();
    $is_branded = article_is_branded( $id );

    $ad_index = 0;
    for ( $i = 0; $i < 6; $i++ ) :
      switch ( $i ) :
        case 0 :
          $injections[] = array(
            'name' => $content_type === 'standard' ? 'advertisement' : 'ampad',
            'type' => 'ad',
            'iteration' => $ad_index++,
            'placeholder' => false,
            'required' => true
          );
          break;

        case 1 :
          if ( $is_branded || get_field('skip_related_content', $id) ) :
            break;
          endif;

          $injections[] = array(
            'name' => 'related-content',
            'type' => 'content',
            'placeholder' => true,
            'required' => true
          );
          break;

        case 2 :
          if ( $is_branded ) :
            break;
          endif;

          if ( $content_type === 'standard' ) :
            $injections[] = array(
              'name' => 'nativead',
              'type' => 'ad',
              'iteration' => 0,
              'placeholder' => false,
              'required' => false
            );
          else :
            // @WORK add conditional for $content_type === 'standard' when ampnativead is added
          endif;
          break;

        case 3 :
          $injections[] = array(
            'name' => $content_type === 'standard' ? 'advertisement' : 'ampad',
            'type' => 'ad',
            'iteration' => $ad_index++,
            'placeholder' => false,
            'required' => false
          );
          break;

        case 4 :
          $injections[] = array(
            'name' => $content_type === 'standard' ? 'advertisement' : 'ampad',
            'type' => 'ad',
            'iteration' => $ad_index++,
            'placeholder' => false,
            'required' => false
          );
          break;

        case 5 :
          $injections[] = array(
            'name' => $content_type === 'standard' ? 'advertisement' : 'ampad',
            'type' => 'ad',
            'iteration' => $ad_index++,
            'placeholder' => false,
            'required' => false
          );
          break;

      endswitch;
    endfor;

    $distance = 0;
    $min_end_slot_distance = 2500;
    $last_node = $nodes->length - 2;
    $injection_index = 0;
    $injection_slots = array();
    foreach ($nodes as $i => $node) :
      if ($node->nodeType !== 1) continue;
      $next_node = $nodes[$i + 2];
      $slot_available = self::check_slot($i, $node, $next_node);
      $distance += self::get_node_distance($node);
      if ($slot_available) :
        $injection_slots[] = array(
          'index' => $injection_index,
          'position' => $i,
          'char_count' => $distance
        );
        $injection_index++;
      endif;
      if ($i == $last_node) :
        $is_min_end_distance = $distance < $min_end_slot_distance;
        $is_short_slots = count($injection_slots) < count($injections);
        if ($is_min_end_distance && $is_short_slots) :
          $injection_slots[] = array(
            'index' => $injection_index,
            'position' => $i,
            'char_count' => $distance
          );
        endif;
      endif;
      $distance += 70;
    endforeach;

    return self::injection_config($injections, $injection_slots);
  }

  /**
   * Create dom placeholder injection
   *
   * @param string $injectionName The injection dom name
   *
   * @return dom <$injectionName-injection></$injectionName-injection>
   */
  static function make_injection_placeholder($doc, $injectionName) {
    return $doc->createElement($injectionName.'-injection');
  }

  /**
   * Replaces dom placeholder injection
   *
   * @param string $injectionName The injection dom name
   * @param string $injectionContent The injection dom content as string
   * @param string $content The content where to inject the string (contains <injectionName>)
   *
   * @return string The adjusted content
   */
  static function replace_injection_placeholder($injectionName, $content, $injectionContent=null) {
    if (!isset($injectionContent) && !$injectionContent) :
      $injectionContent = get_module($injectionName);
    endif;

    return str_replace("<$injectionName-injection></$injectionName-injection>", $injectionContent, $content);
  }

  /**
   * Inject ads and modules into the_content
   *
   * @param string $content The post content
   *
   * @return string The adjusted post content
   */
  function content_injections($content) {
    global $post;

    if (!$post || !is_object($post) || !wag_post_has_infinite($post->ID) || is_feed()) return $content;

    $refresh_ads = get_field( 'enable_sidebar_ad_refresh', $post->ID );
    $data_refresh_ads = !empty($refresh_ads) ? html_data_attr("data-refresh-ads", implode(' ', (array) $refresh_ads)) : '';
    $content_args = self::get_content_args($content);
    $content = $content_args[0];
    $id = $post->ID;
    $article_is_branded = article_is_branded( $id );
    $article_is_video = article_is_video( $id );
    $page = $content_args[1];
    $content = "<div class=\"editor-content\" $data_refresh_ads>$content</div>";
    $content_type = is_amp_context() ? 'amp' : 'standard';

    $dom = new Dom;
    $doc = new \DOMDocument();
    @$doc->loadHTML( mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $nodes = $doc->documentElement->childNodes;
    $getsFranchise = $this->article_has_franchise( $id );

    if ($getsFranchise) :
      $injection = $doc->createElement('franchise-treatment');
      foreach ($nodes as $node) {
        if (!$node->firstChild) {
          // Continue/Skip empty nodes
          continue;
        }

        // Check for iframe wrapper and make sure is a youtube header
        if ($node->hasAttribute('class') && strstr($node->getAttribute('class'), 'iframe-container')) {
          if ($node->firstChild && $node->firstChild->tagName === 'iframe') {
            $src = $node->firstChild->getAttribute('src');
            if ($src && preg_match('/^https:\/\/(?:www\.)?youtube.com\/embed\/[A-z0-9]+/', $src)) {
              $node->parentNode->insertBefore( $injection, $node->nextSibling);
              break;
            }
          }
        }
        // Insert as first element and escape
        $nodes[0]->parentNode->insertBefore($injection, $nodes[0]);
        break;
      }
    else :
      if ( count( $nodes ) > 0 
        && property_exists( $nodes[0], 'firstChild' )
        && $nodes[0]->firstChild 
        && property_exists( $nodes[0], 'tagName' )
        && $nodes[0]->tagName === 'p' ) :
        $html = $doc->saveHTML( $nodes[0] );

        if ( ! preg_match( "#<p><.*?>#", $html ) ) :
          $new_html = brrl_get_module( 'main-2020/drop-cap', $html );
          libxml_use_internal_errors(true); // TODO: GRADY SUPPRESSING XML WARNINGS
          $new_node = simplexml_load_string( $new_html );
          if ($new_node) {
            $import = $doc->importNode( dom_import_simplexml( $new_node ), true );
            $nodes[0]->parentNode->replaceChild( $import, $nodes[0] );
          }
        endif;
      endif;
    endif;

    $injections = self::get_injections($nodes, $id, $content_type);

    foreach (array_reverse($injections, true) as $i => $settings) :
      $name = $settings['name'];
      $iteration = array_key_exists('iteration', $settings) ? $settings['iteration'] : 0;
      $node = $nodes[$settings['target_slot']['position']];
      $data = array(
        'id' => $id
      );

      if ($page) $data['page'] = $page;
      if ($iteration) $data['iteration'] = $iteration;

      if ( ! $article_is_branded && ! $article_is_video && $i === 0 && ( ! $page || $page === 0 ) ) :
        $insert_pre_injection = 'outstream';
      elseif ( ! $article_is_branded && ! $article_is_video && $i === 3 ) :
        $insert_pre_injection = 'featured-content-promo';
      else :
        $insert_pre_injection = false;
      endif;

      $injection = self::create_injection($doc, $name, $data, $settings['props']);

      if ( $injection && ! empty( $injection ) ) :
        $node->parentNode->insertBefore($injection, $node->nextSibling);
        if (!$settings['props']['placeholder']) :
          $settings['injected'] = true;
        endif;

        if ( $insert_pre_injection ) :
          $pre_injection = self::create_injection($doc, $insert_pre_injection, $data );
          if ( ! empty( $pre_injection ) ) :
            $node->parentNode->insertBefore($pre_injection, $node->nextSibling);
          endif;
        endif;
      endif;

    endforeach;

    $content = $doc->saveHTML();

    foreach (array_filter($injections, function($inj) {
      return $inj['props']['placeholder'];
    }) as $i => &$settings) :
      $content = self::replace_injection_placeholder($settings['name'], $content);
      $settings['injected'] = true;
    endforeach;

    if ($getsFranchise) {
      $content = $this->franchise_injection_content($id, $content);
    }

    return $content;
  }

  /**
   * Check if an article will have a franchise blurb
   *
   * @param integer $id The post id
   * @return boolean whether or not article gets franchise treatment
   */
  function article_has_franchise( $post_id ) {
    $article_has_franchise = false;

    $hero_tag = get_field( 'hero_tag', $post_id );
    $tag_1 = get_field( 'tag_1', $post_id );
    $tag_2 = get_field( 'tag_2', $post_id );
    $hero_is_franchise = get_field( 'editorialtag_franchise', "category_$hero_tag" );
    $tag_1_is_franchise = get_field( 'editorialtag_franchise', "category_$tag_1" );
    $tag_2_is_franchise = get_field( 'editorialtag_franchise', "category_$tag_2" );
    $article_is_branded = article_is_branded( $post_id );
    $franchise_type = get_field( 'franchise_type', $post_id );
    $hide_franchise_override = get_field( 'override_franchise_display', $post_id );

    switch ( true ) :
      case ( ( $hero_is_franchise 
        || $tag_1_is_franchise 
        || $tag_2_is_franchise )
        && ! $hide_franchise_override ) :
      case ( $franchise_type === 'branded'
        && $article_is_branded ) :
        $article_has_franchise = true;
        break;

      default :
        $article_has_franchise = false;

    endswitch;

    return $article_has_franchise;
  }

  /**
   * Inject ads and modules into the_content
   *
   * @param number $id The post id
   *
   * @return string check if post ID gets franchise treatment
   */
  function gets_franchise_injection( $post_id ) {
    $hide_franchise_override = get_field( 'override_franchise_display', $post_id );

    if ( $hide_franchise_override ) :
      return false;
    endif;

    return get_franchise( $post_id );
  }

  function franchise_injection_content( $post_id, $content = '' ) {
    $franchise = $this->gets_franchise_injection( $post_id );
    $article_is_branded = article_is_branded( $post_id );

    if ( $franchise || $article_is_branded ) :
      $franchise_content = NULL;

      $franchise_type = get_field( 'franchise_type', $post_id ) ?: 'editorial';
      $override_type = $article_is_branded && $franchise_type === 'branded' ? 'branded' : 'override';
      $franchise_overrides = array_filter( array(
        'description' => get_field( "{$override_type}_franchise_description", $post_id ),
        'logo' 				=> get_field( "{$override_type}_franchise_logo", $post_id ),
        'more_link' 	=> get_field( "{$override_type}_franchise_see_more_link", $post_id ),
        'sponsor'			=> get_field( "{$override_type}_franchise_sponsor", $post_id, false )
      ) );

      if ( $franchise && ! $article_is_branded ) :
        $franchise_content = get_module( 'franchise-blurb', array(
          'type' => 'editorial',
          'franchise' => $franchise['id'],
          'overrides' => $franchise_overrides
        ) );
      elseif ( $franchise_type === 'branded' && ( $article_is_branded && ! empty( $franchise_overrides ) ) ) :
        $franchise_content = get_module( 'franchise-blurb', array(
          'type' => 'branded',
          'overrides' => $franchise_overrides
        ) );
      endif;

      if ( $franchise_content ) :
        return str_replace("<franchise-treatment></franchise-treatment>", $franchise_content, $content);
      endif;
    endif;

    return $content;
  }
}
