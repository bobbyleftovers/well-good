<?php
$style = $style ?? 'dark';
$image = $image ?? false;

$allowed_blocks = array( 'wg/plain-text');
$template = array(
    array( 'wg/plain-text', array(
        'placeholder' => "Add a title",
    )),
    array( 'wg/plain-text', array(
      'placeholder' => "Add a description for the signup form",
  ))
);

if($image) $image = wg_resize( $image['url'], 666, 212, true, 95 );

if($style == 'dark') $class = 'is-dark text-white '. ($image ? 'bg-tan':'bg-tan-dark');
else $class = 'is-light '. ($image ? 'bg-tan':'bg-tan');
?>
<div class="w-1/1 acf-signup-form px-e20 sm:px-e30 pt-e35 pb-e25 md:pt-e40 md:pb-e35 ml:pt-e35 lg:py-e30 my-e25 sm:my-e30 bg-center bg-cover bg-no-repeat <?=$class?>" <?php if($image): ?>style="background-image: url(<?=$image?>)"<?php endif; ?>>
  <div class="text-left">
  <?php if($is_editor): ?>
    <InnerBlocks 
              allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ) ?>"
              template="<?= esc_attr( wp_json_encode( $template ) ) ?>" 
              templateLock="all"/>
  <?php else: 
    $title = $innerBlocks[0] ? ($innerBlocks[0]['innerHTML'] ? trim(strip_tags($innerBlocks[0]['innerHTML'])) : false) : false;
    $text = $innerBlocks[1] ? ($innerBlocks[1]['innerHTML'] ? trim(strip_tags($innerBlocks[1]['innerHTML'])) : false) : false;
    ?>
    <?php if($title): ?><h3 class="text-h4 acf-signup-form__title mb-e10"><?=$title?></h3><?php endif; ?>
    <?php if($text): ?><div class="text-small acf-signup-form__text mb-e15"><?=$text?></div><?php endif; ?>
  <?php endif; ?>
  </div>
  <?php brrl_the_module('main-2020/newsletter-form', array('style' => $style == 'dark' ? 'white' : 'default')); ?>
</div>