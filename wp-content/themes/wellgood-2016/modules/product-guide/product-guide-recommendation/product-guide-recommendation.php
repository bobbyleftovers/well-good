<?php
/**
 * Product guide quiz recommendation
 *
 * A grid of these recommendations are displayed
 * after the quiz is taken
 * @author BarrelNY
 */

$page_id = get_the_id();
$parent_id = wp_get_post_parent_id($page_id);

$data = isset($post->product_guide_recommendation_field) ? $post->product_guide_recommendation_field : null;

$product_id = $data->ID;

$recommendation_title = $data->post_title;
$recommendation_price = get_field('product_price', $product_id);
$recommendation_image = get_field('product_image', $product_id);
$recommendation_description = get_field('product_description', $product_id);
$recommendation_categories = wp_get_object_terms($product_id, 'product_categories');
$recommendation_link = get_field('product_link', $product_id);
$recommendation_link_text = !empty($recommendation_link['product_link_text']) ? $recommendation_link['product_link_text'] : 'Shop Now';
$recommendation_link_url = $recommendation_link['product_link_url'];
$recommendation_link_target_blank = $recommendation_link['product_link_target_blank'] ? ' target="_blank"' : '';
?>

<div class="product-guide-recommendation">

  <div class="product-guide-recommendation__card">
    <div class="product-guide-recommendation__image" style="background-image:url(<?= $recommendation_image['url']; ?>);"></div>
    <div class="product-guide-recommendation__info">
      <div>
        <?php
        require('product-guide-recommendation-category.php'); ?>
      </div>
      <div class="product-guide-recommendation__info--details">
        <h3 class="product-guide-recommendation__info--title"><?= $recommendation_title; ?></h3>
        <h5>$<?= $recommendation_price; ?></h5>
      </div>
    </div>
  </div>

  <article>
    <img src="<?= $recommendation_image['url']; ?>" alt="">
    <h2 class="title">
      <?= $recommendation_title; ?>
    </h2>
    <p class="description"><?= $recommendation_description; ?></p>
    <h3 class="price"><?= $recommendation_price; ?></h3>
    <a href="<?= $recommendation_link_url; ?>" class="link"<?= $recommendation_link_target_blank; ?>>
      <h4><?= $recommendation_link_text; ?></h4>
    </a>
  </article>

</div>
