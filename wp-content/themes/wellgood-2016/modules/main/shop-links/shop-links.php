<?php
global $post;

$link  = isset( $post->shop_links_field ) ? $post->shop_links_field : null;
$format = isset( $post->shop_links_sub_field ) ? $post->shop_links_sub_field : null;

if ( empty( $link ) ) {
    return;
}

$url = $link['url'];
$title = $link['title'];
$image = $link['image'];
$image_width = $link['image_width'];
$image_height = $link['image_height'];
$price = $link['price'];
$hover_text = $link['hover_text'];
$hover_text_bg_color = $link['hover_text_bg_color'];

$button_text = empty( $link['button_text'] ) ? __( 'Buy Now', 'wellandgood' ) : $link['button_text'];
$button_color = $link['button_color'];
$button_text_color = $link['button_text_color'];

$modifiers = [''];

if(!empty($hover_text)) {
  $modifiers[] = 'hover-text';
}


?>
<div class="shop-links<?= implode(' shop-links--', $modifiers); ?>" <?= html_data_attr("data-module-init", "shop-links") ?>>
    <div class="shop-links__inner">
        <a target="_blank" href="<?php echo $url; ?>" class="shop-links__link" <?= html_data_attr("data-vars-event", "shop link") ?> <?= html_data_attr("data-vars-info", $title) ?>>
            <div class="shop-links__thumbnail <?= $format && !empty($hover_text) == 'standard' ? 'js-mobile-slider' : ''; ?>">
	           	 <?php
			            remove_filter( 'the_content', 'image_module_posts', 100 );
									if ( !is_feed() ) {
                    $image_attrs = array( 
                      'data-vars-event="shop link image"',
                      "data-vars-info=\"$title\""
                    );
			            } else {
				          	$image_attrs = ""; 
                  }
                  the_module( 'image', $image, $image, '', '', true, $image_attrs );

                  if(!is_amp_endpoint()) {
                    require('shop-links-content.php');
                  }
                ?>
            </div>
            <?php if (!empty($title)) { ?>
              <div class="shop-links__title" <?= html_data_attr("data-vars-event", "shop link text") ?> <?= html_data_attr("data-vars-info", $title) ?>><?= $title ?></div>
            <?php } ?>

            <?php
              // For amp we'll just lay this content below the title & price
              if(is_amp_endpoint()) {
                require('shop-links-content.php');
              }
            ?>

            <?php if (!empty($price)) {
              echo '<div class="shop-links__price">' . $price . '</div>';
            } ?>

        </a>
    </div>
</div>
