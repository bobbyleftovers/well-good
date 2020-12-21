<?php
  $sponsored_links = get_option(SMARTFORMAT__SPONSORED_LINKS);
  if ($sponsored_links) {
    foreach ($sponsored_links as &$sponsored_link) {
      $thumbnail_attachment_url = wp_get_attachment_url($sponsored_link['thumbnail_attachment_id']);
      if ($thumbnail_attachment_url !== false) {
        $sponsored_link['thumbnail_attachment_url'] = $thumbnail_attachment_url;
      } else {
        $sponsored_link['thumbnail_attachment_url'] = '';
      }
    }
  }

  $variables = array(
    'thumbnail' => __('Thumbnail'),
    'title' => __('Title'),
    'link' => __('Link'),
    'advertiser' => __('Advertiser'),
    'selectThumbnail' => __('Select thumbnail'),
    'remove' => __('Remove'),
    'sponsoredLinks' => json_encode($sponsored_links)
  );
  wp_register_script('admin-sponsored-links', plugin_dir_url(__FILE__) . 'admin-sponsored-links.js', array('jquery'));
  wp_localize_script('admin-sponsored-links', 'sponsoredLinks', $variables);
  wp_enqueue_script('admin-sponsored-links');
?>

<div id="admin-sponsored-link">
</div>

<div>
<button type="button" id="<?php echo SMARTFORMAT__SPONSORED_LINKS ?>-button" class="button add-sponsored-link"><?php _e('Add Sponsored Link') ?></button>
</div>
