<?php
/**
 * Content Filters
 *
 * Filter `the_content`
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 6.7.11
 */


namespace WG\Content;

class Content_Filters {
  function __construct() {
    add_filter('the_content', array($this,'embed_wrapper'));
    add_filter('the_content', array($this,'add_nofollow'), 9999, 1);
    add_filter( 'the_content', array($this,'image_module_posts'), 100 );
  }

  /**
   * Filters 'img' elements in post content to add image module.
   */
  function image_module_posts( $content ) {
    if ( is_amp_endpoint() ) :
      return $content;
    endif;

    if ( is_single() ) :

      if ( ! preg_match_all( '/<[^>]*class="[^"]*\wp-image\b[^"]*"[^>]*>/i', $content, $matches ) ) :
        return $content;
      endif;

      $selected_images = $attachment_urls = array();

      foreach( $matches[0] as $image ) :
        if ( preg_match( '/src=("[^"]*")/i', $image, $image_src ) && ( $attachment_url = $image_src[1] ) ) :
          $doc = new \DOMDocument;
          $doc->loadHTML($image, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

          preg_match('/(?<=src=")[^"]*/i', $image, $src_matches);
          preg_match('/(?<=class=")[^"]*/i', $image, $class_matches);
          $classes = explode(' ', $class_matches[0]);
          array_push($classes, 'js-inline-lazy-load');
          $doc_img = $doc->getElementsByTagName('img')[0];
          $doc_img->setAttribute('class', implode(' ', $classes));
          $doc_img->setAttribute('data-src', $src_matches[0]);
          $doc_img->setAttribute('src', get_template_directory_uri() . '/assets/img/spacer.gif');
          $img = $doc->saveHTML();
          /*
          * If exactly the same image tag is used more than once, overwrite it.
          * All identical tags will be replaced later with 'str_replace()'.
          */
          $selected_images[$image] = $img;
          // Overwrite the url when the same image is included more than once.
          $attachment_urls[$img] = true;
        endif;
      endforeach;

      foreach ( $selected_images as $image => $img ) :
        $content = str_replace( $image, $img, $content );
      endforeach;

    endif;

    return $content;
  }

  /**
  * Add nofollow to all external links
  */
  function add_nofollow( $content ) {
    $dom = new \DOMDocument;

    if ( is_feed() ) :
      return $content;
    endif;

    // Supress errors
    libxml_use_internal_errors( TRUE );

    // Convert to UTF-8
    @$dom->loadHTML( mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    // Restore previous state
    libxml_clear_errors();

    $a = $dom->getElementsByTagName( 'a' );
    $urlparts = parse_url( home_url() );
    $host = @$urlparts['host'];
    $commerce_url_values = get_field( 'commerce_urls', 'options' );
    $is_branded_post = has_tag( 'branded' ) || has_term( 'branded', 'backend_tag' );
    $no_follow_rel = 'nofollow';
    $sponsor_rel = 'sponsored';

    foreach( $a as $anchor ) :
      // Extract from XML
      $hrefEl = $anchor->attributes->getNamedItem( 'href' );
      if ($anchor->attributes->getNamedItem( 'ignore-nofollow' )) {
        continue;
      }

      if (empty($hrefEl)) {
        continue;
      }
      $href = $hrefEl->nodeValue;
      $oldRel = $anchor->attributes->getNamedItem( 'rel' );
      $oldRelNodeValues = ! empty( $oldRel ) ? $oldRel->nodeValue : '';
      $old_rel_array = ! empty( $oldRelNodeValues ) ? explode( ' ', $oldRelNodeValues ) : array();
      $new_rel = '';

      // Test to see a current href type
      $is_outbound_url = !preg_match( '/^https?:\/\/(?>www.)?wellandgood.com\/?/', $href ) && !preg_match( '/^https?:\/\/(?>www.)?' . preg_quote( $host, '/' ) . '/', $href );
      $commerce_urls = ! empty( $commerce_url_values ) ? wp_list_pluck( $commerce_url_values, 'url' ) : array();
      $is_commerce_url = false;

      if ( ! empty( $commerce_urls ) ) :
        foreach( $commerce_urls as $commerce_url ) :
          if ( stripos( strtolower( $href ), $commerce_url ) === 0 ) :
            $is_commerce_url = true;
            break;
          endif;
        endforeach;
      endif;

      /**
       * Condition:
       * - Is commerce url
       * Add rel="nofollow"
       */
      if ( $is_commerce_url && $is_outbound_url ) :
        $new_rel = $no_follow_rel;
      endif;

      /**
       * Condition:
       * - Is branded post AND
       * - Is external url OR is commerce url
       * Add rel="sponsored"
       */
      if ( $is_branded_post && ( $is_outbound_url || $is_commerce_url ) ) :
        $new_rel = $sponsor_rel;
      endif;

      if ( ! empty( $new_rel ) ) :
        $old_rel_array[] = $new_rel;
        $new_rel_array = ! empty( $old_rel_array ) ? array_unique( $old_rel_array ) : $old_rel_array;

        $new_rel_attrs = implode( ' ', array_unique( $new_rel_array ) );

        // Import to XML
        $newRelAttr = $dom->createAttribute( 'rel' );
        $noFollowNode = $dom->createTextNode( $new_rel_attrs );
        $newRelAttr->appendChild( $noFollowNode );
        $anchor->appendChild( $newRelAttr );
      endif;
    endforeach;


    $html = $dom->saveHTML();
    $html = str_replace('%7B%7B', '{{', $html);
    $html = str_replace('%7D%7D', '}}', $html);
    return $html;
  }

  /**
   * Wrap all <iframes> in a specific div so that we can force their aspect ratio
   */
  function embed_wrapper($content) {
    // match any iframes
    $pattern = '~<iframe[^>]*src\s*=\s*"?https?:\/\/[^\s"\/]*youtube.com(?:\/[^\s"]*)?"?[^>]*>.*?<\/iframe>~';
    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
      $wrappedframe = '<div class="iframe-container">' . $match . '</div>';
      $content = str_replace($match, $wrappedframe, $content);
    }

    return $content;
  }
}
