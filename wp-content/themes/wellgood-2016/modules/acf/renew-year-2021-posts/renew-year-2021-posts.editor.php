<?php
$allowed_blocks = array( 'core/paragraph', 'core/heading' );
$template = array(
    array( 'core/heading', array(
        'placeholder' => "Week",
    )),
    array( 'core/paragraph', array(
      'placeholder' => "Description here...",
    ))
);
$bg_color = get_field('color_bg', $_POST['post_id']);
if($image) $image = $image['sizes']['newsletter-card'];
?>
<div class="acf-renew-year-2021-posts border border-gray">
  <div class="flex">
    <div class="relative text-center flex-grow p-e30 w-1/2 text-white flex flex-col items-center justify-center" style="background-color: <?=$bg_color?>">
      <InnerBlocks 
            allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
            template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
            templateLock="all"/>    
    </div>
    <div class="relative flex-grow w-1/2 px-e20 py-e30 max-h-viewport-1/2 min-h-viewport-1/2 overflow-scroll text-left">
    <?php foreach($posts as $post): ?>
        <div class="renew-year-2021-posts__post mb-e50">
          <div class="text-h3 mb-e4 mt-e0 p-e0 text-left">
            <?= $post->post_title ?>
          </div>
          <div class="text-default mb-e30 text-left">
            <?= wp_trim_words($post->post_content, 15, '');?>
          </div>
          <div class="renew-year-2021-posts__post__img overflow-hidden relative"> 
            <?php $image = get_the_post_thumbnail_url( $post, 'article') ?: wag_get_fallback_image()['url']; ?>
            <img src="<?=$image?>" class="block">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>