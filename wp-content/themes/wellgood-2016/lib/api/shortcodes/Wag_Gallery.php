<?php

/**
 * Shortcode to print markup for GDPR form.
 * @return array Markup from gdpr-form module.
 */

namespace WG\API\Shortcodes;

use WG\API\Shortcodes\Custom_Shortcode;

class Wag_Gallery extends Custom_Shortcode {

  protected $shortcode = 'wag_gallery';

  function shortcode($attr, $output = '') {
    $props = shortcode_atts(array(
      'order' => 'ASC',
      'orderby' => 'post__in',
      'ids' => '',
      'columns' => 3,
      'size' => 'thumbnail',
      'hide_on_mobile' => '',
      'show_on_mobile' => ''
    ), $attr);

    if (empty($props['ids'])) {
      return '';
    }

    $order = $props['order'] ?? 'ASC';
    $orderby = $props['orderby'] ?? 'menu_order ID';
    $hide_on_mobile = explode(',', $props['hide_on_mobile']);
    $show_on_mobile = explode(',', $props['show_on_mobile']);

    $attachments = get_posts(array(
      'post__in' => explode(',', $props['ids']),
      'post_status' => 'inherit',
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'order' => $order,
      'orderby' => $orderby,
      'posts_per_page' => -1
    ));

    $gallery_items = array_map(function($attachment) use ($props) {
      $id = $attachment->ID;
      return array(
        'id' => $id,
        'image' => wp_get_attachment_image_src($id, $props['size']),
        'caption' => wp_get_attachment_caption($id),
        'has_link' => filter_var(wp_get_attachment_caption($id), FILTER_VALIDATE_URL) // Check if caption is url
      );
    }, $attachments);

    if (is_amp_endpoint()) :
      ob_start();
      ?>
      <div class="post__gallery post__gallery--mobile">
        <?php foreach ($gallery_items as $gallery_item):
          $img = $gallery_item['image'];
          $caption = $gallery_item['caption'];
          $has_link = $gallery_item['has_link'];

          if (in_array($gallery_item['id'], $hide_on_mobile)) {
            continue;
          }
          ?>

          <?php if ($has_link): ?>
            <a href="<?= $caption ?>" class="post__gallery-link">
          <?php endif; ?>

          <amp-img layout="responsive"
                  src="<?= $img[0] ?>"
                  alt="<?= $caption ?>"
                  width="<?= $img[1] ?>"
                  height="<?= $img[2]?>"
          ></amp-img>

          <?php if ($has_link): ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php

      return ob_get_clean();
    endif;

    ob_start(); ?>
    <div class="post__gallery post__gallery--columns-<?= $props['columns'] ?>">
      <?php foreach ($gallery_items as $gallery_item):
        $img = $gallery_item['image'];
        $caption = $gallery_item['caption'];
        $has_link = $gallery_item['has_link'];
        $class = 'post__gallery-item';

        if (in_array($gallery_item['id'], $hide_on_mobile)) {
          $class .= ' post__gallery-item--hide-mobile';
        }

        if (in_array($gallery_item['id'], $show_on_mobile)) {
          $class .= ' post__gallery-item--show-mobile';
        }
        ?>
      <div class="<?= $class ?>">
        <?php if ($has_link): ?>
        <a href="<?= $caption ?>" class="post__gallery-link" target="_blank">
        <?php else: ?>
        <div class="post__gallery-link">
      <?php endif ?>
        <img src="<?= esc_attr($img[0]) ?>"
            alt="<?= $has_link ? sprintf(__('Navigate to %s', 'wellgood-2016'), $caption) : $caption ?>"
            class="no-pin"
        >
        <?php if ($has_link): ?>
        </a>
      <?php else: ?>
        </div>
      <?php endif ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
  }
}
