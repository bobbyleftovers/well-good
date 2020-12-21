<?php
$id = $attrs['id'];
$size = $attrs['sizeSlug'];
$image = wp_get_attachment_image_src($id, 'full'); 
$align = $align ?? 'contain';
$caption = get_string_between($innerHTML, '<figcaption>', '</figcaption>');
if(!$caption || $caption == '') $caption = wp_get_attachment_caption($id);
if($align === 'full'){
  $image[0] = wg_resize( $image[0], 1440, false, false, 80 );
  $placeholder_w = 720;
  $placeholder_h = 340;
} else {
  $placeholder_w = 330;
  $placeholder_h = 195;
}

?>

<?php if ($align === 'full'): ?></div><?php endif; ?>

<div class="relative my-e40">
    <div class="trends-2021-base-image trends-2021-base-image--<?=$align?> block relative">
      <div class="absolute top-0 left-0 w-1/1 h-1/1">
        <?php
          the_module('image', array(
            'image_src' => $image[0],
            'image_src_retina' => $image[0],
            'image_width' => $image[1],
            'image_height' => $image[2],
            'is_video' => false
          ));

          brrl_the_module('main-2020/base-image-placeholder', array(
            'width' => $placeholder_w,
            'height' => $placeholder_h,
            'class' => "opacity-80 bg-secondary--$post->ID"
          ));
        ?>
       
      </div>
      <div class="trends-2021-base-image__padding relative w-1/1" style="padding-bottom: <?=100/$image[1]*$image[2]?>%"></div>
    </div>
    <?php if($caption && $caption != ''): ?><div class="text-small text-center mt-e10"><?=$caption?></div><?php endif; ?>
</div>

<?php if ($align === 'full'): ?><div class="trends-2021-container"><?php endif; ?>