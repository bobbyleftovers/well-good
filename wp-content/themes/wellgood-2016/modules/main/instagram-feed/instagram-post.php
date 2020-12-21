<?php
$instagram_image_url = esc_url( $item['large'] );
$instagram_image_retina_url = esc_url( $item['original'] );
$instagram_title = esc_attr( $item['description'] );
$handle = get_field('instagram_handle', 'options');
?>

<li>
  <a href="<?= esc_url( $item['link'] ); ?>" target="_blank" class="instagram-feed-image-link">
    <span class="instagram-feed-image-wrapper">
      <?php the_module('image', $instagram_image_url, $instagram_image_retina_url, $instagram_title); ?>
    </span>
  </a>
</li>
