<?php
/**
 * Product within the product grid
 * @author BarrelNY
 *
 * required from `product-guide-grid.php`
 *
 * @param index_phase      - The largest cycle, includes 4 `index_group`
 * @param index_group      - A group for each of the types of ads
 * @param product_id       - Product ID
 * @param product_title    - Product title
 * @param index_item       - Product index
 * @param sponsored_item   - (bool) Whether or not item is sponsored
 * @param big_boi	       -
 * @param order            - Product order, if sponsored, it is generated
 *                           in the sponsor module, otherwise it is `$index_item + 1`
 */

$product_classes = array('product-guide-grid__item', 'product-guide-grid--order-'.$order);

/**
 * Sponsored product data
 */
if ($sponsored_item) :
  $sponsored_product_object = $sponsored_content['product_object'];
  $sponsored_product_burst_copy = $sponsored_content['product_burst_copy'];
  $sponsored_product_sponsor_name = $sponsored_content['product_sponsor_name'];
  $sponsored_product_sponsor_logo = $sponsored_content['product_sponsor_logo'];
  $sponsored_product_sponsor_relationship = $sponsored_content['product_sponsor_relationship'];
  $sponsored_product_id = $sponsored_product_object->ID;
  $sponsored_product_title = $sponsored_product_object->post_title;
  $product_description = get_field('product_description', $sponsored_product_id);
  $product_categories = wp_get_object_terms($sponsored_product_id, 'product_categories');
  $product_price = get_field('product_price', $sponsored_product_id);
  $product_image = get_field('product_image', $sponsored_product_id);
  $product_link = get_field('product_link', $sponsored_product_id);
  $product_link_text = !empty($product_link['product_link_text']) ? $product_link['product_link_text'] : 'Shop Now';
  $product_link_url = $product_link['product_link_url'];
  $product_link_target_blank = $product_link['product_link_target_blank'];

  array_push($product_classes, 'product-guide-grid__item--sponsored');

/**
 * Normal product data
 */
else :
  $product_description = get_field('product_description', $product_id);
  $product_categories = wp_get_object_terms($product_id, 'product_categories');
  $product_price = get_field('product_price', $product_id);
  $product_image = get_field('product_image', $product_id);
  $product_link = get_field('product_link', $product_id);
  $product_link_text = !empty($product_link['product_link_text']) ? $product_link['product_link_text'] : 'Shop Now';
  $product_link_url = $product_link['product_link_url'];
  $product_link_target_blank = $product_link['product_link_target_blank'] ? ' target="_blank"' : '';
  if ($big_boi) {
    array_push($product_classes, 'product-guide-grid__item--sponsored');
  }
endif;
?>

<li class="<?= implode(' ', $product_classes); ?>">

  <div class="product-guide-grid__card">
    <div class="product-guide-grid__card--image" style="background-image:url(<?= $product_image['url']; ?>);"></div>
    <div class="product-guide-grid__card--info">
      <?php
      if ($sponsored_item) : ?>
        <div class="product-guide-grid__card--sponsor">
          <small><?= $sponsored_product_sponsor_relationship; ?></small>

          <div class="product-guide-grid__card--sponsor_logo" style="background-image:url(<?= $sponsored_product_sponsor_logo['url']; ?>);"></div>
        </div>
      <?php
      endif; ?>
      <div class="product-guide-grid__card--category">
        <?php
        require('product-guide-grid-category.php'); ?>
      </div>
      <div class="product-guide-grid__card--details">
        <h3 class="product-guide-grid__card--title">
          <?php
          if ($sponsored_item) :
            echo $sponsored_product_title;
          else:
            echo $product_title;
          endif; ?>
        </h3>
        <?php
        if ($product_price) : ?>
          <h5 class="product-guide-grid__card--price price price--usd"><?= $product_price; ?></h5>
        <?php
        endif; ?>
      </div>
    </div>
    <?php
    if ($sponsored_item && $sponsored_product_burst_copy) : ?>
      <div class="product-guide-grid__card--burst">
        <div>
          <h6 class="product-guide-grid__card--burst-copy"><?= $sponsored_product_burst_copy; ?></h6>
          <?= get_svg('product-burst-solid', array(
            'role' => 'banner'
          )); ?>
        </div>
      </div>
    <?php
    endif; ?>
  </div>

  <article>
    <img src="<?= $product_image['url']; ?>" alt="">
    <h2 class="title">
      <?php
      if ($sponsored_item) :
        echo $sponsored_product_title;
      else:
        echo $product_title;
      endif; ?>
    </h2>
    <p class="description"><?= $product_description; ?></p>
    <h3 class="price"><?= $product_price; ?></h3>
    <a href="<?= $product_link_url; ?>" class="link"<?= $product_link_target_blank; ?>>
      <h4><?= $product_link_text; ?></h4>
    </a>
  </article>

</li>
