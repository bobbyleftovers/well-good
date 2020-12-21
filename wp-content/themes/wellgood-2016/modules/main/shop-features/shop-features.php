<?php
global $post;
$selected_module = isset($post->shop_features_field) ? $post->shop_features_field : null;
$post_type = isset($post->shop_features_sub_field) ? $post->shop_features_sub_field : null;
$is_recipe = $post_type == 'recipe' ? true : false;
$format = $selected_module['display_format'] ? $selected_module['display_format'] : 'standard' ;

if ( ! empty( $selected_module ) ) :
	?>
    <div class="shop-features <?= $format; ?>">
        <?php if ( ! empty( $selected_module['headline'] ) ): ?>
            <h2 class="<?= $is_recipe ? 'module-heading post-grid__headline' : 'shop-features__headline' ?>">
                <span class="<?= !$is_recipe ? 'shop-features__headline-text' : '' ?>"><?php echo $selected_module['headline'] ?></span>
            </h2>
        <?php endif; ?>
        <div class="shop-features__inner">
			<?php foreach ($selected_module['shop_links'] as $link) :
                the_module( 'shop-links', $link, $format );
			endforeach; ?>
        </div>
    </div>
<?php endif; ?>
