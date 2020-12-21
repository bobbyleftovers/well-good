<?php

  /**
   * A function to replace the <figure> tag with it's contained <img> tag.
   * This is a specific requirement of the MSN feed.
   *
   * @param string $content - The html markup to be sanitized
   * @return string $dom->saveHTML() - The sanitized html markup object
   */

  function replace_figure($content, $post_id) {

    $content = wpautop( $content );
    $dom = new DOMDocument();
    @$dom->loadHTML( mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // DOMdoc still doesn't like HTML5 tags, so needing to surpress errors/notices here

    $figures = $dom->getElementsByTagName('figure');

    if($figures->length > 0){
      while ($figures->length){

        $figure = $figures->item(0);

        $image = $figure->getElementsByTagName('img')->item(0);
        $caption = $figure->getElementsByTagName('figcaption')->item(0);
        $image_credit = 'Well+Good';

        if( $caption ){
          $image_credit = $caption->nodeValue;
        }

        if( $image ){
          $image_url = $image->getAttribute('src');
          $image_id = get_image_id($image_url, $post_id);

          $image_title = $image_id && get_the_title($image_id) ? get_the_title($image_id) : get_the_title($post_id);

          $image->setAttribute("data-found-alt",esc_attr($image_title));
          $image->setAttribute('data-portal-copyright',esc_attr($image_credit));
          $image->setAttribute('data-has-syndication-rights','1');
          // $image->setAttribute('data-license-id','');
          // $image->setAttribute('data-licensor-name','Well+Good');
        }

        $figure->parentNode->replaceChild($image,$figure);

      }
    }

    return $dom->saveHTML($dom->documentElement);
  }