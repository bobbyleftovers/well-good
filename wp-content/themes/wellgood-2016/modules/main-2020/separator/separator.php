<?php
$class = $class ?? '';
$transparent = $transparent ?? false;
if(!is_array($args)){
  $text = $args;
  $transparent = false;
}
if($text == '') $text = false;
?>
<div class="<?= $class ?> separator w-full flex justify-stretch mt-e14 sm:mt-e30 md:mt-e50 lg:mt-e28 <?php if(!$transparent): ?>mb-e14 sm:mb-e45 lg:mb-e28<?php endif; ?> items-center" style="justify-content: stretch;  flex-direction: row;
  align-items: center;">
  <?php if(!$transparent): ?>
    <span class="border-b border-tan block" style="flex: 1; "></span>
    <?php if($text): ?><span class="text-tag text-gray block px-e5"><?= $text ?></span><?php endif; ?>
    <span class="border-b border-tan block" style="flex: 1; "></span>
  <?php endif; ?>
</div>
