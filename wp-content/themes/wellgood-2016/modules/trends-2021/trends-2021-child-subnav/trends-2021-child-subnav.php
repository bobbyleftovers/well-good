<?php
$spacing_bottom = "mb-e37 md:mb-e38 ml:mb-e55";
?>
<div  data-module-init="trends-2021-child-subnav" class="trends-2021-child-subnav bg-body-background--<?=$post->ID?> relative sm:px-0 text-center border-b border-tan-medium w-full max-w-full overflow-hidden text-center <?=$spacing_bottom?>">
  <div class="absolute top-0 left-0 w-e25 md:w-e40 h-full trends-2021-child-subnav__left"></div>
  <div class="inline-block w-auto max-w-full">
    <div class="trends-2021-child-subnav__nav h-e45 md:h-e50 z-40 flex flex-no-wrap items-center justify-start overflow-auto">
      <div class="pl-e25 md:pl-e40 flex-shrink-0 h-e25"></div>
      <?php foreach($menu as $link):?>
        <a class="avoid-anchor-link text-gray-dark pr-e20 sm:px-e20 w-auto flex-shrink-0 nowrap inline-block hover:opacity-70 text-link cursor-pointer transition duration-500"
            href="#<?=$link['slug']?>"
            @click.prevent="scrollTo('#<?=$link['slug']?>')"
            >
            <?=$link["label"]?>
        </a>
      <?php endforeach; ?>
      <div class="pr-e25 md:pr-e40 flex-shrink-0 h-e25"></div>
    </div>
  </div>
  <div class="absolute top-0 right-0 w-e60 sm:w-e25 md:w-e40 h-full trends-2021-child-subnav__right"></div>
</div>

<?php
$bg = hexToRgb($colors[$post->ID]['body-background']);
?>

<style>
  .trends-2021-child-subnav__left {
    background: linear-gradient(270deg, rgba(<?=$bg['r']?>, <?=$bg['g']?>, <?=$bg['b']?>, 0.0001) 3.65%, rgba(<?=$bg['r']?>, <?=$bg['g']?>, <?=$bg['b']?>, 0.7) 100%);
  }

  .trends-2021-child-subnav__right {
    background: linear-gradient(270deg, rgba(<?=$bg['r']?>, <?=$bg['g']?>, <?=$bg['b']?>, 0.7) 3.65%, rgba(<?=$bg['r']?>, <?=$bg['g']?>, <?=$bg['b']?>, 0.0001) 100%);
  }
</style>
