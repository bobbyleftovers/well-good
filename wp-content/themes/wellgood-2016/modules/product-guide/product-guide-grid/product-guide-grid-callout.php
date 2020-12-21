<?php
/**
 * Serving the various types of sponsored content for the gift guide
 * @author BarrelNY
 *
 * @param index_phase - The largest cycle, includes 4 `index_group`
 * @param index_group - A group for each of the types of ads
 * @param index_item - Product index
 * @param ad_placement - int, determines whether the ad is on the top, middle or bottom
 */

$callout_type = $sponsored_content['callout_type'];

$callout_default_quiz = get_field('product_guide_campaign_quiz', $parent_id);
$callout_sponsor = $callout_default_quiz['product_guide_campaign_quiz_sponsor'];
$callout_sponsor_name = $callout_sponsor['product_guide_campaign_quiz_sponsor_name'];
$callout_sponsor_logo = $callout_sponsor['product_guide_campaign_quiz_sponsor_logo'];
$callout_sponsor_relationship = $callout_sponsor['product_guide_campaign_quiz_sponsor_relationship'];

if ($callout_type == 'default') :
  $callout_headline = $callout_default_quiz['product_guide_campaign_quiz_promotion_headline'];
  $callout_call_to_action = $callout_default_quiz['product_guide_campaign_quiz_promotion_call_to_action'];
  $callout_background = $callout_default_quiz['product_guide_campaign_quiz_promotion_background'];
else :
  $callout_headline = $sponsored_content['callout_custom_headline'];
  $callout_call_to_action = $sponsored_content['callout_custom_action'];
  $callout_background = $sponsored_content['callout_custom_background'];
endif;
?>

<li class="product-guide-grid__item product-guide-grid__item--callout product-guide-grid--order-<?= $order; ?>" style="background-image:url(<?= $callout_background['url']; ?>);">
  <div class="product-guide-grid__item--callout--content">
    <div class="product-guide-grid__item--callout--sponsor">
      <small><?= $callout_sponsor_relationship; ?></small>
      <div class="product-guide-grid__item--callout--sponsor-logo" style="background-image:url(<?= $callout_sponsor_logo['url']; ?>);"></div>
    </div>
    <h2 class="product-guide-grid__item--callout--headline">
      <?= $callout_headline; ?>
    </h2>
    <div class="product-guide-grid__item--callout--action">
      <a class="modal__open--quiz"><?= $callout_call_to_action; ?></a>
    </div>
  </div>
</li>

