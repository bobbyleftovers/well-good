<?php
  $url = $url ?? '/';
  $image = $image ?? '';
  $class = $class ?? '';
  $cta = $cta ?? '';
?>

<a href="<?=$url?>" class="group rich-tag-yt__channer-card relative bg-tan w-full overflow-hidden">
            <div class="transform origin-center top-1/2 left-1/2 absolute -translate-x-1/2 -translate-y-1/2 transition duration-1000 w-full h-full bg-cover bg-center bg-no-repeat"
              style="background-image:url(<?=$image?>)"></div>
            <div class="absolute p-e16 sm:p-e20 lg:p-e30 w-full text-center bottom-0 left-0">
              <span href="_blank" class="text-link text-white underline cursor-pointer  group-hover:opacity-70 transition duration-300"><?=$cta?></span>
            </div>
          </a>
