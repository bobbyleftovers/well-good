<div class="wg-inline-shopable pb-e40 border-b border-tan mt-e40">
  <!-- Title -->
  <?php if($title && $title != ''):?>
    <h2 class="text-h2 wg-inline-shopable__title mb-e20"><?=$title?></h2>
  <?php endif; ?>

  <!-- Image -->
  <?php if($image):?>
    <figure class="block w-full wg-inline-shopable__figure p-0 m-0 mb-e25">
      <img src="<?=$image['url']?>" class="block w-full" alt="<?=$image['alt']?>">
      <figcaption class="text-center text-big text-gray-light block mt-e10"><?=$image['caption']?></figcaption>
    </figure>
  <?php endif; ?>

  <!-- Subtitle -->
  <?php if($subtitle && $subtitle !== ''): ?>
    <h3 class="text-h4 mb-e5 wg-inline-shopable__subtitle"><?=$subtitle?> <?= $price ? '— $'.number_format($price,2) : '' ?></h3>
  <?php endif; ?>

  <!-- Content -->
  <?php if($content && $content !== ''): ?>
    <div class="text-big mb-e5 wg-inline-shopable__content"><?=$content?></div>
  <?php endif; ?>

  <!-- CTA -->
  <?php if($cta): ?>
    <?php brrl_the_module('main-2020/base-button', array(
      'text' => $cta['title'], 
      'tag' => 'a', 
      'href' => $cta['url'],
      'target' => $cta['target'],
      'class' => 'mt-e15'
    )) ?>
  <?php endif; ?>
</div>