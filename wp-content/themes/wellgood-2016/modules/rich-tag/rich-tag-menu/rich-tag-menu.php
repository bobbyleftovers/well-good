
<div class="relative sm:px-0 text-centerborder-b border-b border-tan-medium bg-white w-full max-w-full overflow-hidden text-center">
  <div class="absolute top-0 left-0 w-e25 md:w-e40 h-full rich-tag-menu__left"></div>
  <div class="inline-block w-auto max-w-full">
    <div class="rich-tag-menu h-e50 z-40 flex flex-no-wrap items-center justify-start overflow-auto">
      <div class="pl-e25 md:pl-e40 flex-shrink-0 h-e25"></div>
      <?php foreach($menu as $term): ?>
        <a class="pr-e20 sm:px-e20 w-auto flex-shrink-0 nowrap inline-block text-seafoam-dark hover:opacity-70 text-link cursor-pointer transition duration-500"
            href="<?=get_term_link($term)?>">
            <?=$term->name?>
        </a>
      <?php endforeach; ?>
      <div class="pr-e25 md:pr-e40 flex-shrink-0 h-e25"></div>
    </div>
  </div>
  <div class="absolute top-0 right-0 w-e60 sm:w-e25 md:w-e40 h-full rich-tag-menu__right"></div>
</div>
