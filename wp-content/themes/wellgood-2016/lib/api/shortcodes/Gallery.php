<?php

namespace WG\API\Shortcodes;

class Gallery {

  function __construct(){
    add_filter('post_gallery', array($this,'gallery_shortcode'), 10, 2);
  }

  /**
   * Add filter to [gallery] shortcode
   */
  function gallery_shortcode($output, $attr) {
    global $post;
  
    if ( isset( $attr['layout'] ) && $attr['layout'] == 'grid' ) {
      extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => ''
      ), $attr));
  
      $id = intval($id);
      if ('RAND' == $order) $orderby = 'none';
  
      if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  
        $attachments = array();
        foreach ($_attachments as $key => $val) {
          $attachments[$val->ID] = $_attachments[$key];
        }
      }
  
      $output .= "<div class='post__gallery post__gallery--columns-{$attr['columns']}'>\n";
  
      foreach ($attachments as $id => $attachment) {
        $img = wp_get_attachment_image_src($id, $attr['size']);
        $caption = wp_get_attachment_caption($id);
        $has_link = filter_var($caption, FILTER_VALIDATE_URL);
  
        $output .= "<div class='post__gallery-item'>";
        // Validate if caption is url
        if ($has_link) {
          $output .= "<a class='post__gallery-item-link' href='${caption}'>";
        }
  
        $output .= '<div class="post__image-wrapper">';
  
        $output .= sprintf('<img src="%1$s" alt="%2$s" class="%3$s">', $img[0], ($has_link) ? sprintf(__('Navigate to %s', 'wellgood-2016'), $caption) : $caption, ($has_link) ? 'no-pin' : '');
  
        $output .= '</div>';
  
        if ($has_link) {
          $output .= "</a>\n";
        }
  
        $output .= "</div>\n";
      }
  
      $output .= "</div>\n";
  
    }
  
    return $output;
  }
}