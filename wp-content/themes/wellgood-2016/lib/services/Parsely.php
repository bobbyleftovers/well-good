<?php

namespace WG\Services;

class Parsely {

  static private $secret = 'w5ztterVB03LGZJLfXS0hf3EvQBuFFIWew9hmVQxthU';
  static private $apikey = 'wellandgood.com';
  static private $parsely_api = 'https://api.parsely.com/v2';

  function __construct(){
    add_action('wp_footer', array( $this, 'inject_scripts' ), 5 );
    add_filter('amp_post_template_metadata', array($this,'add_amp_post_template_metadata'));
    add_filter('gets_pasley_url_tax_base', array($this, 'gets_category_base'), 10, 1);
  }

  /**
   * Inject scripts
   *
   * @return void
   */
  function inject_scripts () {
    ?>
    <!-- START Parse.ly Include: Standard -->
    <div id="parsely-root" style="display: none">
      <span id="parsely-cfg" data-parsely-site="wellandgood.com"></span>
    </div>
    <script>
        var parselyTrackSubscriptionEventQueue = [];
        function trackParselyTrackSubscription() {
          if (window.PARSELY && window.PARSELY.loaded) {
            window.PARSELY.conversions.trackSubscription();
          } else {
            parselyTrackSubscriptionEventQueue.push();
          }
        }
          var parselyCallbacks = {
              callbacks: [],
              loaded: false,
              init: function() {
                  this.callbacks.map(function(c) {
                      c(PARSELY);
                  });
                  this.loaded = true;

                  for (var i = 0; i < parselyTrackSubscriptionEventQueue.length; i++) {
                    PARSELY.conversions.trackNewsletterSignup();
                  }
              },
              onload: function(c) {
                  if (this.loaded) {
                      c(PARSELY);
                  } else {
                      this.callbacks.push(c);
                  }
              }
          };
          var PARSELY = {
              onload: parselyCallbacks.init.bind(parselyCallbacks)
          };
    </script>
    <script>
      (function(s, p, d) {
        var h=d.location.protocol, i=p+"-"+s;
        var e=d.getElementById(i), r=d.getElementById(p+"-root");
        var u=h==="https:"?"d1z2jf7jlzjs58.cloudfront.net":"static."+p+".com";
        if (e) return;
        e = d.createElement(s); e.id = i; e.async = true;
        e.src = h+"//"+u+"/p.js"; r.appendChild(e);
      })("script", "parsely", document);
    </script>
    <!-- END Parse.ly Include: Standard -->

    <!-- Parse.ly metadata -->
    <script type="application/ld+json">
      <?php echo get_parsely_meta(); ?>
    </script>
    <!-- END Parse.ly metadata -->
    <?php
  }



  function add_amp_post_template_metadata($metadata) {
      global $post;
      $post_author = get_userdata( $post->post_author );
      $metadata_override = self::get_parsely_meta(false);

      /*
      * Note: The Parsely author entry returns empty on some AMP posts,
      * and opted to manually add post author to meta according to the pattern
      * in the plugin rather than debugging at length.
      */
      if ( $post_author ) {
          $metadata_override['author'] = array(
              '@type' => 'Person',
              'name'  => html_entity_decode( $post_author->display_name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
          );
      }
    return $metadata_override;
  }

  function gets_category_base ($taxonomy_slug) {
    return !in_array($taxonomy_slug, array());
  }

  static function get_parsely_key_url ($post) {

    $path = parse_url(get_the_permalink($post->ID))['path'];

    return 'https://www.wellandgood.com'.$path;
  }

  static function get_parsely_thumb() {
    $thumb_id = false;
    $thumb = '';

    if ( is_page() || is_single() ) {
        if ( is_page_template( 'templates/page-council-hub.php' ) || is_page_template( 'templates/page-location-hub.php' ) ) {
            $header = get_field( 'header' );
            if ( ! empty( $header ) && ! empty( $header['ID'] ) ) {
                $thumb_id = $header['ID'];
            }
        } else {
            $thumb_id = get_post_thumbnail_id( get_the_ID() );
        }

        if ( ! empty( $thumb_id ) ) {
            $src = wp_get_attachment_image_src( $thumb_id, 'article' );
            if ( ! empty( $src ) ) {
                $thumb = $src[0];
            }
        }
    }

    return $thumb;
  }

  static function get_parsely_headline() {
      if ( is_home() || is_front_page() || is_page() || is_single() ) {
          return get_the_title();
      }  elseif ( is_category() || is_tag() ) {
          return single_term_title( '', false );
      }

      return '';
  }

  static function get_parsely_url() {
      if ( is_home() || is_front_page() ) {
          return home_url();
      }

      if ( is_page() || is_single() ) {
          return get_permalink();
      }

      if ( is_category() || is_tag() ) {
          return get_term_link( get_queried_object() );
      }
  }

  static function get_parsely_meta($pretty_print = true) {

      $meta = array(
          '@context' => 'http://schema.org',
          '@type' => is_single() ? 'NewsArticle' : 'WebPage',
          'headline' => self::get_parsely_headline(),
          'url' => self::get_parsely_url(),
      );

      $published = get_the_date('Y-m-d\TH:i:s.000\Z');
      $modified = get_the_modified_date('Y-m-d\TH:i:s.000\Z');
      if ($published == $modified) :
          $modified = '';
      endif;

      $thumb = self::get_parsely_thumb();
      if ( ! empty( $thumb ) ) {
          $meta['thumbnailUrl'] = $thumb;
      }

      if ( is_single() ) {
          $post_id = get_the_ID();

          // Tags
          $post_tags = array();
          $hero_tag = get_field( 'hero_tag', $post_id );
          $tag_1 = get_field( 'tag_1', $post_id );
          $tag_2 = get_field( 'tag_2', $post_id );
          if ( $hero_tag ) :
            $post_tags[] = $hero_tag;
          endif;
          if ( $tag_1 ) :
            $post_tags[] = $tag_1;
          endif;
          if ( $tag_2 ) :
            $post_tags[] = $tag_2;
          endif;
          $post_tags = array_map( function( $category_id ) {
            return get_category( $category_id );
          }, $post_tags );

          $terms_backend_tags = ! empty( get_the_terms( $post_id, 'backend_tag' ) ) ? get_the_terms( $post_id, 'backend_tag' ) : array();
          $backend_tags = array_values( array_filter( $terms_backend_tags, function( $backend_tag ) {
            $term_tax = $backend_tag->taxonomy;
            $term_id = $backend_tag->term_id;

            $exposed = get_field( 'backend_tag_parsely_expose', "{$term_tax}_{$term_id}" );
            return $exposed;
          } ) );

          $terms_disclaimers = ( ! empty(get_the_terms( $post_id, 'disclaimer' ) ) ? get_the_terms( $post_id, 'disclaimer' ) : array() );
          if ( is_array( $terms_disclaimers ) ) :
            $disclaimers = array_values(array_filter($terms_disclaimers, function($disclaimer) {
              $term_tax = $disclaimer->taxonomy;
              $term_id = $disclaimer->term_id;

              $exposed = get_field('disclaimer_parsely_expose', "{$term_tax}_{$term_id}");
              return $exposed;
            }));
          else :
            $disclaimers = array();
          endif;

          $tags = array_values( array_unique( array_map( function( $tag ) {
            return $tag->name;
          }, array_merge( $post_tags, $backend_tags, $disclaimers ) ) ) ) ?: array();

          // Vertical
          $vertical = $hero_tag ? get_vertical( $hero_tag, TRUE ) : '';

          // Data
          $meta['mainEntityOfPage'] = get_permalink();
          $meta['dateCreated'] = $published;
          $meta['articleSection'] = $vertical;
          $meta['creator'] = get_the_author();
          $meta['keywords'] = $tags;
          $meta['author'] = get_the_author();
          $meta['datePublished'] = $published;
          $meta['image'] = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : wag_get_fallback_image('url');
          $meta['publisher'] = array(
            '@type' => 'Organization',
            'name' => 'Well+Good',
            'url' => get_home_url(),
            'logo' => array(
              '@type' => 'imageObject',
              'url' => get_field('logo_graphic','option')['url']
            )
          );
          $meta['dateModified'] = $modified;
      }

      if(!$pretty_print) {
          return $meta;
      } else {
          return json_encode( $meta, JSON_PRETTY_PRINT );
      }
  }

  static function hydrate_posts(&$posts, $only_local_posts = false){

    $cache = array(
      'titles' => array(),
      'urls' => array(),
      'ids' => array()
    );

    foreach($posts as $k => &$post){

      if( in_array($post->url, $cache['urls']) || in_array($post->title, $cache['titles'])){
        unset($posts[$k]);
        continue;
      }

      $path = (array) explode('/',trim(parse_url($post->url)['path'], '/'));
      $post->slug = end($path);
      $args = array(
        'name'        => $post->slug,
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
      );
      $queried_posts = get_posts($args);
      $post->excerpt = '';

      if( $queried_posts ) :
        $wp_post = $queried_posts
      [0];
        if(article_is_branded($wp_post->ID)) {
          unset($posts[$k]);
          continue;
        }
        $post->image_thumbnail = get_the_post_thumbnail_url($wp_post,'thumbnail');
        $post->ID = $wp_post->ID;
        $post->excerpt = strip_shortcodes(strip_tags($wp_post->post_content));
        $post->date = date('F d, Y',strtotime($wp_post->post_date ?? $post->pub_date));
        $post->title = verify_title_case( $wp_post->post_title, $wp_post->post_date, get_field( 'override_automatic_title_casing', $wp_post->ID ) );

      elseif($only_local_posts):
        unset($posts[$k]);
        continue;

      elseif(!is_production()):
        $post->image_thumbnail = $post->image_url;
        $post->ID = 0;
        $post->date = date('F d, Y',strtotime($post->pub_date ?? 'April 30, 2020'));
        $post->excerpt = "This is a placeholder. On live, real text will appear. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tortor nibh, mattis sit amet magna in, bibendum venenatis mi. Fusce vulputate consequat mi vitae rhoncus. Suspendisse potenti.";
        $post->title = verify_title_case( $post->title );

      endif;
      
      $post->id = $post->ID;
      $post->excerpt = wp_trim_words($post->excerpt, 20);

      if( in_array($post->url, $cache['urls']) || in_array($post->title, $cache['titles']) || ($post->ID && in_array($post->ID, $cache['ids']))){
        unset($posts[$k]);
        continue;
      }

      $cache['urls'][] = $post->url;
      $cache['titles'][] = $post->title;
      $cache['ids'][] = $post->ID;
    }

    return $posts;
  }

  static function parsely_remote_get($endpoint, $params = array(), $options = array()){

    if(isset($params['max']) && !isset($options['max'])) $options['max'] = $params['max'];

    if(isset($params['limit'])) {
      if(!isset($options['max'])) $options['max'] = $params['limit'];
      $params['limit'] = $params['limit']*2;
    }

    if(!isset($options['only_local_posts'])) $options['only_local_posts'] = false;
    if(!isset($options['request_iteration'])) $options['request_iteration'] = 1;
    else $options['request_iteration']++;

    $params_string = "";
    foreach($params as $key => $value){
      $params_string .= '&'.$key.'='.$value;
    }

    $url = self::$parsely_api.$endpoint."?secret=".self::$secret."&apikey=".self::$apikey.$params_string;
    $response = wp_remote_get($url);
    $body = json_decode($response['body']);
    $data = (array) $body->data;
    $posts = self::hydrate_posts($data, $options['only_local_posts']);

    if(isset($options['max'])) {

      if($options['request_iteration'] <= 3 && isset($params['limit']) && sizeof($posts) < $options['max']){
        $posts = self::parsely_remote_get($endpoint, $params, $options);
      }

      $posts = array_slice( $posts, 0, $options['max'] );
    }

    return $posts;
  }

  static function get_top_posts($args){

    if(!is_array($args)){
      $args = array(
        'max' => $args
      );
    }

    $posts = self::parsely_remote_get(
      "/top/posts", 
      $args
    );

    return $posts;
  }

  static function search($params = array()){
    $params['sort'] = 'score';
    $params['page'] = isset($params['page']) ? $params['page'] : 1;
    $posts = self::parsely_remote_get(
      "/search", 
      $params, 
      array(
        'only_local_posts' => true
        )
    );
    return $posts;
  }

  static function get_related($args){

    $posts = self::parsely_remote_get(
      "/related", 
      $args
    );

    return $posts;
  }

}
