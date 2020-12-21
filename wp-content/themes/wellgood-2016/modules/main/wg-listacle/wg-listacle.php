<?php
  $has_title = $title && $title !== '';
?>
<div class="wg-listacle-wrapper">
  <!-- Main title -->
  <?php if($has_title ): ?>
    <h2 class="text-h2 border-t border-tan mt-e40 sm:mb-e3 pt-e25"><?=$title?></h2>
  <?php endif; ?>

  <ol class="mb-e40 wg-listacle list-none block <?= $has_title ? 'has-title':'sm:mt-e40 border-t no-title' ?>">

      <!-- List -->
      <?php foreach($list as $key => $li): ?>
      <li class="py-e20 sm:py-e35 sm:flex justify-between">

        <div>
          <!-- Title -->
      <h3 class="wg-listacle-title text-h4 mb-e15 sm:mb-e5"><?php if(!is_feed()): ?><?=$key+1?>. <?php endif; ?><?=$li['title']?></h3>

          <!-- Image -->
          <div class="md:hidden">
            <img src="<?=$li['image']['sizes']['recipe-large']?>" class="block w-full" alt="<?=$li['image']['alt']?>">
          </div>

          <!-- Content -->
          <?php if($li['content'] && $li['content'] !== ''): ?>
            <div class="wg-listacle__content text-big mb-e20"><?=$li['content']?></div>
          <?php endif; ?>

          <!-- CTA -->
          <?php if($li['cta']): ?>
            <?php brrl_the_module('main-2020/base-button', array(
              'text' => $li['cta']['title'],
              'tag' => 'a',
              'href' => $li['cta']['url'],
              'target' => $li['cta']['target']
              )) ?>
          <?php endif; ?>
        </div>

        <!-- Image -->
        <?php if($li['image'] && !is_feed() && !is_amp_endpoint()): ?>
          <div class="ml-gutter wg-listacle__image hidden md:block">
            <img src="<?=$li['image']['sizes']['recipe-large']?>" class="block w-full" alt="<?=$li['image']['alt']?>">
          </div>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
  </ol>
</div>
