<?php
/**
 * Header for gift guide
 * @author BarrelNY
 */

global $post;

$page_id = $post->product_guide_header_field ? $post->product_guide_header_field : get_the_id();
$parent_id = get_the_parent_id($page_id);
$page_title = get_the_title($page_id);
$back_to_everything_link = get_permalink($parent_id);

$children_args = array(
	'post_parent' => $parent_id,
  'post_type' => 'page',
  'orderby' => 'menu_order'
);
$verticals = get_children($children_args);
$header_classes = array('product-guide-header');

$social_share_networks = get_field('product_guide_campaign_share', $parent_id);
$campaign_quiz = get_field('product_guide_campaign_quiz', $parent_id);
$campaign_quiz_title = $campaign_quiz['product_guide_campaign_quiz_title'];
$campaign_quiz_sponsor = $campaign_quiz['product_guide_campaign_quiz_sponsor'];
$campaign_quiz_sponsor_name = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_name'];
$campaign_quiz_sponsor_logo = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_logo'];
$campaign_quiz_sponsor_relationship = $campaign_quiz_sponsor['product_guide_campaign_quiz_sponsor_relationship'];

$is_index = $page_id == $parent_id;

/**
 * Variables: Index page
 */
if ($is_index) :
  array_push($header_classes, 'product-guide-header--index');

/**
 * Variables: Category pages
 */
else :
  array_push($header_classes, 'product-guide-header--category');

endif;
?>

<header class="<?= implode(" ", $header_classes); ?>" data-module-init="product-guide-header">
  <div class="product-guide-header__top">
    <div class="product-guide-header__top--logo">
      <a href="<?= $back_to_everything_link; ?>"<?php if (!$is_index) { echo ' class="load-products" data-url="'.$back_to_everything_link.'" data-id="'.$parent_id.'" data-title="Everything"';} ?>>
        <div class="product-guide-header__top--logo-copy">
          <span>WELL+GOOD</span>
        </div>
      </a>
    </div>
    <div class="product-guide-header__top--hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <?php
  the_module('product-guide/product-guide-subnav', $verticals, $page_id); ?>
  <div class="product-guide-header__bottom">
    <?php
    if (!empty($campaign_quiz)) : ?>
      <div class="product-guide-header__quiz-callout">
        <?php
        if ($campaign_quiz_sponsor) : ?>
          <div class="product-guide-header__quiz-callout--sponsor">
            <small class="product-guide-header__quiz-callout--sponsor_relationship"><?= $campaign_quiz_sponsor_relationship; ?></small>
            <div class="product-guide-header__quiz-callout--sponsor_logo" style="background-image: url(<?= $campaign_quiz_sponsor_logo['url']; ?>);"></div>
          </div>
        <?php
        endif; ?>
        <?php
        if ($campaign_quiz_title) : ?>
          <div class="product-guide-header__quiz-callout--title">
            <?= $campaign_quiz_title; ?>
          </div>
        <?php
        endif; ?>
        <div class="product-guide-header__quiz-callout--link">
          <span class="modal__open--quiz">Take the quiz</span>
        </div>
      </div>
    <?php
    endif; ?>
    <?php
    if ($social_share_networks) : ?>
      <div class="product-guide-header__bottom--social">
        <?php
        the_module('product-guide/product-guide-share', $page_id); ?>
      </div>
    <?php
    endif; ?>
  </div>
</header>
