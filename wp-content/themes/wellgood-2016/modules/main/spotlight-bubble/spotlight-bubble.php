<?php
if( have_rows('spotlight_items', 'option') ):
?>
  <div class="spotlight-bubble-container container xs-down:pl-0 xs-down:pr-0 <?= isset($classes) ? $classes : ''; ?>" data-module-init="spotlight-bubble" totalitems="<?= count(get_field('spotlight_items', 'option'));?>" v-cloak>
    <nav class="spotlight-bubble theme-main-2020 bg-seafoam-light mt-e5 mb-e5 sm:mt-e10 py-e10 sm:py-0 md:py-e5 md:mt-e0 lg:mb-e10 flex items-center justify-between <?= isset($classes) ?? '' ?>">
      <button :class="{ invisible: totalItems == 1  }" @click="prev" class="sm:ml-e0 ml:ml-e10 spotlight-bubble__nav left w-e35 h-e35 inline-flex items-center leading-none justify-center">
        <span class="icon-arrow-left-thin text-seafoam-dark block"></span>
      </button>
      <div class="spotlight-bubble-items w-full" ref="items">
      <?php while( have_rows('spotlight_items', 'option') ) : the_row(); ?>
        <?php
          $contents = get_sub_field('contents');
          $link = get_sub_field('link');
          $cta = get_sub_field('cta');
        ?>
        <div class="spotlight-bubble__item w-full px-e10 md:px-e20 tracking-wide text-byline text-center">
          <div class="mx-auto max-w-e200 sm:max-w-none spotlight-bubble__item-wrap">
            <?= $contents; ?> <a class="text-seafoam-dark underline ml-e5 inline" href="<?= $link ?>"><?= $cta ?></a>
          </div>
        </div>
      <?php endwhile; ?>
      </div>
      <button :class="{ invisible: totalItems == 1 }" @click="next" class="sm:mr-e0 ml:mr-e10 spotlight-bubble__nav right w-e35 h-e35 inline-flex items-center leading-none justify-center">
        <span class="rotate-180 text-seafoam-dark transform block icon-arrow-left-thin"></span>
      </button>
    </nav>
  </div>
<?php endif; ?>
