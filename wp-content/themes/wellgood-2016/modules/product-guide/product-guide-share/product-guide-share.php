<?php
/**
 * Social sharing
 * Product guide social sharing
 * @author BarrelNY
 */

///
// Variables: Universal
//
$page_id = $post->product_guide_share_field ? $post->product_guide_share_field : get_the_id();
$parent_id = get_the_parent_id($page_id);

$social_share_networks = get_field('product_guide_campaign_share', $parent_id);
$social_share_url = urlencode(get_the_permalink($page_id));
$social_share_title = wg_esc_url(get_the_title($page_id));
$social_share_twitter_handle = get_twitter_handle();

$share_settings = get_field('product_guide_share_settings', $parent_id);
$share_email_subject = $share_settings['email_subject'] ? $share_settings['email_subject'] : $social_share_title;
$share_email_body = $share_settings['email_body'] ? $share_settings['email_body'] . ' ' . get_the_permalink($page_id) : wg_esc_url( get_the_excerpt($page_id) . " \n\n" . get_the_permalink($page_id) );


$is_index = $page_id == $parent_id;

/**
 * Variables: Index page
 */
if ($is_index) :
  $social_share_image = get_field('product_guide_campaign_hero_background', $parent_id);
  $social_share_image_encoded = urlencode($social_share_image['url']);

/**
 * Variables: Category pages
 */
else :
  $social_share_image = get_field('product_guide_category_hero_background', $page_id);
  $social_share_image_encoded = urlencode($social_share_image['url']);

endif;

$social_data = array(
  'facebook'  => array(
    'icon'      => 'icon-facebook',
    'new_tab'   => true,
    'link'      => '//www.facebook.com/sharer/sharer.php?u=' . $social_share_url,
    'data'      => null
  ),
  'twitter'   => array(
    'icon'      => 'icon-twitter',
    'new_tab'   => true,
    'link'      => '//twitter.com/share?text=' . $social_share_title . ' via ' . $social_share_twitter_handle . '&amp;url=' . $social_share_url,
    'data'      => null
  ),
  'pinterest' => array(
    'icon'      => 'icon-pinterest-p',
    'new_tab'   => true,
    'link'      => '//pinterest.com/pin/create/link/?url=' . $social_share_url . '&amp;description=' . $social_share_title . '&amp;media=' . $social_share_image_encoded,
    'data'      => null
  ),
  'email'     => array(
    'icon'      => 'icon-envelope',
    'new_tab'   => false,
    'link'      => 'mailto:?subject=' . $share_email_subject . '&body=' . $share_email_body,
    'data'      => null
  ),
  'flipboard' => array(
    'icon'      => '',
    'new_tab'   => true,
    'link'      => 'https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=' . $social_share_title . '&amp;url=' . $social_share_url,
    'data'      => 'data-flip-widget="shareflip"'
  )
);
?>

<span>
  Share:
</span>
<ul>
  <?php
  foreach($social_share_networks as $network) :
    $link_args = array();
    $link_href = $social_data[$network]['link'];
    $link_icon = $social_data[$network]['icon'];
    $link_tab = $social_data[$network]['new_tab'];
    $link_data = $social_data[$network]['data'];

    if ($link_tab) {
      array_push($link_args, 'target="_blank"');
    }
    if ($link_data) {
      array_push($link_args, $link_data);
    }
    $link_args = implode(" ", $link_args);
    ?>

    <li class="product-guide-header__hero--intro--social-icon">
      <a href="<?= $link_href; ?>" class="<?= $link_icon; ?>" <?= $link_args; ?>></a>
    </li>

  <?php
  endforeach; ?>
</ul>
