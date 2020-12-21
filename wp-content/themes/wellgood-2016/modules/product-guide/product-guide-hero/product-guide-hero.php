<?php
/**
 * Hero for gift guide
 * @author BarrelNY
 */

global $post;

$page_id = $post->product_guide_hero_field ? $post->product_guide_hero_field : get_the_id();
$parent_id = get_the_parent_id($page_id);
$page_title = get_the_title($page_id);
$back_to_site = get_field('product_guide_back_to_site', $parent_id);
$back_to_site_text = $back_to_site['product_guide_back_to_site_text'];
$back_to_site_link = $back_to_site['product_guide_back_to_site_link'] == 'home' ? get_home_url($page_id) : $back_to_site['product_guide_back_to_site_url'];

$hero_classes = array('product-guide-hero');

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
  $campaign_description = get_field('product_guide_campaign_description', $parent_id);
  $campaign_sponsor = get_field('product_guide_campaign_sponsor', $parent_id);
  $campaign_sponsor_name = $campaign_sponsor['product_guide_campaign_sponsor__name'];
  $campaign_sponsor_relationship = $campaign_sponsor['product_guide_campaign_sponsor__relationship'];
  $campaign_sponsor_logo = $campaign_sponsor['product_guide_campaign_sponsor__logo'];
  $hero_background = get_field('product_guide_campaign_hero_background', $parent_id);
  $display_sponsor = $campaign_sponsor_relationship && $campaign_sponsor_logo;

  array_push($hero_classes, 'product-guide-hero--index');

/**
 * Variables: Category pages
 */
else :
  $category_sponsor = get_field('product_guide_category_sponsor', $page_id);
  $category_sponsor_name = $category_sponsor['product_guide_category_sponsor__name'];
  $category_sponsor_relationship = $category_sponsor['product_guide_category_sponsor__relationship'];
  $category_sponsor_logo = $category_sponsor['product_guide_category_sponsor__logo'];
  $hero_background = get_field('product_guide_category_hero_background', $page_id);
  $category_description = get_field('product_guide_category_description', $page_id);

  array_push($hero_classes, 'product-guide-hero--category');

endif;
?>

<div class="<?= implode(" ", $hero_classes); ?>">
  <?php
  if ($is_index) : ?>
    <div class="product-guide-hero__top" style="background-image:url(<?= $hero_background['url']; ?>)">
      <div class="product-guide-hero__mobile">
          <div class="product-guide-hero__mobile--logo">
            <a href="<?= $back_to_site_link; ?>">
              <span>WELL+GOOD</span>
            </a>
          </div>
          <div class="product-guide-hero__mobile--hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
      </div>
      <div class="product-guide-hero__top--logo">
        <?= get_svg('holidaygiftgiude-logo', array(
          'role' => 'banner'
        )); ?>
      </div>
      <?php
      if (!empty($campaign_quiz)) : ?>
        <div class="product-guide-hero__quiz-callout">
          <?php
          if ($campaign_quiz_sponsor) : ?>
            <div class="product-guide-hero__quiz-callout--sponsor">
              <small class="product-guide-hero__quiz-callout--sponsor_relationship"><?= $campaign_quiz_sponsor_relationship; ?></small>
              <div class="product-guide-hero__quiz-callout--sponsor_logo" style="background-image: url(<?= $campaign_quiz_sponsor_logo['url']; ?>);"></div>
            </div>
          <?php
          endif; ?>
          <?php
          if ($campaign_quiz_title) : ?>
            <div class="product-guide-hero__quiz-callout--title">
              <?= $campaign_quiz_title; ?>
            </div>
          <?php
          endif; ?>
          <div class="product-guide-hero__quiz-callout--link">
            <span class="modal__open--quiz">Take the quiz</span>
          </div>
        </div>
      <?php
      endif; ?>
    </div>
    <div class="product-guide-hero__intro">
      <div class="product-guide-hero__intro--top">
        <div class="product-guide-hero__intro--logo">
          <a href="<?= $back_to_site_link; ?>">
            <div class="product-guide-hero__intro--copy">
              <span>WELL+GOOD</span>
            </div>
            <div class="product-guide-hero__intro--back">
              <?= $back_to_site_text; ?>
            </div>
          </a>
        </div>
      </div>
      <div class="product-guide-hero__intro--callout<?php if (!$display_sponsor) { echo ' no-sponsor'; } ?>">
        <p><?= $campaign_description; ?></p>
        <?php
        if ($display_sponsor) : ?>
          <hr>
          <div class="product-guide-hero__intro--sponsor">
            <h6><?= $campaign_sponsor_relationship; ?></h6>
            <div class="product-guide-hero__intro--sponsor_logo" style="background-image:url(<?= $campaign_sponsor_logo['url']; ?>);"></div>
          </div>
        <?php
        endif; ?>
      </div>
      <div class="product-guide-hero__intro--bottom">
        <div class="product-guide-hero__intro--arrow">
          <span>
            <h6>Scroll to explore</h6>
          </span>
          <?= get_svg('scroll-right-arrow', array(
            'role' => 'button'
          )); ?>
        </div>
        <?php
        if ($social_share_networks) : ?>
          <div class="product-guide-hero__intro--social">
            <?php the_module('product-guide/product-guide-share'); ?>
          </div>
        <?php
        endif; ?>
      </div>
    </div>
  <?php
  else : ?>
    <div class="product-guide-hero__top" style="background-image: url(<?= $hero_background['url']; ?>)">
      <div class="product-guide-hero__top--title">
        <h1><?= $page_title; ?></h1>
      </div>
      <div class="product-guide-hero__top--description">
        <p><?= $category_description; ?></p>
      </div>
      <?php
      if ($category_sponsor_relationship && ($category_sponsor_logo || $category_sponsor_name)) : ?>
        <hr>
        <div class="product-guide-hero__top--sponsor">
          <?php
          if ($category_sponsor_logo) : ?>
            <h5 class="product-guide-hero__top--sponsor_relationship"><?= $category_sponsor_relationship; ?></h5>
            <div class="product-guide-hero__top--sponsor_logo" style="background-image:url(<?= $category_sponsor_logo['url']; ?>);"></div>
          <?php
          else : ?>
            <h5 class="product-guide-hero__top--sponsor_relationship">
              <?= $category_sponsor_relationship; ?>
              <?= $category_sponsor_name; ?>
            </h5>
          <?php
          endif; ?>
        </div>
      <?php
      endif; ?>
      <div class="dark-overlay"></div>
    </div>
  <?php
  endif; ?>
</div>
