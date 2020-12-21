<?php
/**
 * Sidebar for product guide, visible only on
 * portrait breakpoint
 * @author BarrelNY
 */

$page_id = get_the_id();
$parent_id = get_the_parent_id();

$social_share_networks = get_field('product_guide_campaign_share', $parent_id);
$back_to_site = get_field('product_guide_back_to_site', $parent_id);
$back_to_site_text = $back_to_site['product_guide_back_to_site_text'];
$back_to_site_link = $back_to_site['product_guide_back_to_site_link'] == 'home' ? get_home_url() : $back_to_site['product_guide_back_to_site_url'];
?>

<div class="product-guide-sidebar loading" data-module-init="product-guide-sidebar">
  <div class="product-guide-sidebar__menu">
    <div class="product-guide-sidebar__close" aria-label="close sidebar">
    <?= get_svg('close', array(
      'role' => 'button'
    )); ?>
    </div>
    <div class="product-guide-sidebar--quiz-callout">
      <div class="product-guide-sidebar--quiz-copy">
        <h2 class="product-guide-sidebar--quiz-question">
          Need gift help?
        </h2>
        <h6 class="product-guide-sidebar--quiz-link">
          <a class="product-guide-sidebar--quiz-launch modal__open--quiz">Take the quiz</a>
        </h6>
      </div>
      <?= get_svg('product-burst', array(
        'role' => 'banner'
      )); ?>
    </div>
    <?php
    if ($social_share_networks) : ?>
      <div class="product-guide-sidebar--social">
        <?php
        the_module('product-guide/product-guide-share'); ?>
      </div>
    <?php
    endif; ?>
    <div class="product-guide-sidebar--back">
      <h6>
        <a href="<?= $back_to_site_link; ?>"><?= $back_to_site_text; ?></a>
      </h6>
    </div>
  </div>
  <div class="product-guide-sidebar__mask"></div>
</div>
