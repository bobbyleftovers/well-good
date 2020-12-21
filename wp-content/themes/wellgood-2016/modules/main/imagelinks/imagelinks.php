<?php
global $post;

$content = isset($post->imagelinks_field) ? $post->imagelinks_field : false;

if ( is_array( $content ) ) :
  $title                = $content['hotspot_title'];
  $post_content         = $content['hotspot_content'];
  $cta_text             = $content['hotspot_cta_text'];
  $cta_link             = $content['hotspot_cta_link'];
  $sponsored_label      = $content['sponsored_by_label'];
  $sponsored_logo       = $content['sponsor_logo'];
  $sponsored_link       = $content['sponsor_link'];
  $product_image        = $content['product_image'];
  $product_image_title  = $content['product_image_title'];
  $product_name         = $content['product_name'];
  $product_price        = $content['product_price'];
  ?>

  <div class="imagelinks-content">
    <div class="imagelinks-meta">
      <h3 class="imagelinks-meta__title"><?= $title; ?></h3>
      <div class="imagelinks-meta__description"><?= $post_content; ?></div>
      <?php if($sponsored_logo || $cta_link) : ?>
        <div class="imagelinks-meta__footer">
          <?php if ($cta_link && $cta_text) : ?>
            <div class="imagelinks-cta">
              <a class="imagelinks-cta__btn" target="_blank" href="<?= $cta_link; ?>" data-vars-event="image link cta" data-vars-info="<?= $title; ?>">
                <?= $cta_text ?? 'Buy Now'; ?>
              </a>
            </div>
          <?php endif; ?>
          <?php if($sponsored_logo && $sponsored_link) : ?>
            <div class="imagelinks-sponsor">
              <p class="imagelinks-sponsor__label"><?= $sponsored_label; ?></p>
              <a class="imagelinks-sponsor__link" href="<?= $sponsored_link; ?>"><img class="imagelinks-sponsor__logo no-pin" src="<?= $sponsored_logo['url']; ?>" alt="<?= $sponsored_label; ?>"></a>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="imagelinks-product">
      <?php if ($product_image_title) : ?>
        <h4 class="imagelinks-product__label h3"><?= $product_image_title; ?></h4>
      <?php endif; ?>
      <?php
      // the imagelinks pugin manipulates the dom using source code, which prevents us from using the image module for this
      if($product_image) : ?>
        <div class="imagelinks-product__image" style="background-image: url('<?= $product_image['sizes']['medium']; ?>')"></div>
      <?php endif; ?>
      <?php if ($product_name) : ?>
        <h5 class="imagelinks-product__title h3"><?= $product_name; ?></h5>
      <?php endif; ?>
      <?php if ($product_price) : ?>
        <p class="imagelinks-product__price h3"><?= $product_price; ?></p>
      <?php endif; ?>
    </div>
  </div>
<?php
endif;
