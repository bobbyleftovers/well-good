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

$sponsored_article_object = $sponsored_content['article_object'];
$sponsored_article_id = $sponsored_article_object->ID;
$sponsored_article_title = $sponsored_article_object->post_title;
$sponsored_article_author = get_user_by( 'id', $sponsored_article_object->post_author)->display_name;
$sponsored_article_image = ($sponsored_content['article_background_image']['article_image_source'] == 'article') ? get_the_post_thumbnail_url($sponsored_article_id) : $sponsored_content['article_background_image']['article_custom_image']['url'];
$sponsored_article_url = get_the_permalink($sponsored_article_id);
$sponsored_article_sponsor_name = $sponsored_content['article_sponsor_name'];
$sponsored_article_sponsor_logo = $sponsored_content['article_sponsor_logo'];
$sponsored_article_sponsor_relationship = $sponsored_content['article_sponsor_relationship'];
$sponsored_article_target_blank = $sponsored_content['article_target_blank'] ? ' target="_blank"' : '';
$product_categories = array(get_term($sponsored_content['article_related_category'], 'product_categories'));
?>

<li class="product-guide-grid__item product-guide-grid__item--article product-guide-grid--order-<?= $order; ?>" style="background-image:url(<?= $sponsored_article_image; ?>);">
  <div class="product-guide-grid__item--article--content">
    <div class="product-guide-grid__item--article--sponsor">
      <small><?= $sponsored_article_sponsor_relationship; ?></small>
      <div class="product-guide-grid__item--article--sponsor_logo" style="background-image:url(<?= $sponsored_article_sponsor_logo['url']; ?>);"></div>
    </div>
    <div class="product-guide-grid__item--article--category">
      <?php
      $post_store = $post;
      require('product-guide-grid-category.php');
      $post = $post_store; ?>
    </div>
    <a href="<?= $sponsored_article_url; ?>"<?= $sponsored_article_target_blank; ?>>
      <h3 class="product-guide-grid__item--article--title">
        <?= $sponsored_article_title; ?>
      </h3>
      <h5 class="product-guide-grid__item--article--author">By <?= $sponsored_article_author; ?></h5>
    </a>
  </div>
</li>
