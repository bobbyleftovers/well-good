<div class="trends-2021-slideshow__cell trends-2021-slideshow__cell--<?=$frame_index?>">
          <figure class="trends-2021-slideshow__figure <?= $figure_classes ?>">
            <svg class="svg-frame <?= $svg_classes ?>" viewBox="<?= $viewbox ?>" fill="none" xmlns="http://www.w3.org/2000/svg">
              <clipPath id="frame-<?= $path_id ?>" class="svg-frame__clip-path">
                <path fill-rule="evenodd" clip-rule="evenodd" d="<?= $format === 'large' ? $clip_paths[$frame_index - 1] : $sm_clip_path ?>" stroke="transparent"/>
              </clipPath>
              <?php
              if ($format === 'large') {?>
                <g class="svg-frame__accents" clip-path="url(#frame-<?= $path_id ?>)">
                  <image clip-path="url(#frame-<?= $path_id ?>)" class="svg-frame__img" xlink:href="<?= wg_resize( $image['url'], 656, 906, true, 70 ) ?>" preserveAspectRatio="xMidYMin slice"></image>
                  <g class="svg-frame__accents" filter="url(#filter__<?= $path_id ?>)" clip-path="url(#frame-<?= $path_id ?>)">
                    <rect class="svg-frame__accent svg-frame__accent--<?= $frame_index ?> <?= $fill_class ?>" <?= ($frame_index === 2) ? 'rx="110.015"' : null ?>/>
                  </g>
                </g><?php
              } else {?>
                <image clip-path="url(#frame-<?= $path_id ?>)" class="svg-frame__img" xlink:href="<?= wg_resize( $image['url'], 646, 788, true, 75 ) ?>" preserveAspectRatio="xMidYMin slice"></image><?php
              }?>
              <defs>
                <filter id="filter__<?= $path_id ?>"  filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                  <feGaussianBlur stdDeviation="<?= $deviation ?>" result="effect1_foregroundBlur"/>
                </filter>
              </defs>
            </svg>
            <div class="trends-2021-slideshow__caption <?= $caption_classes ?> <?= $copy_color_class ?> <?= ($format === 'small') ? 'font-sans' : 'text-caption' ?>">
              <?= $image['caption'] ?>
            </div>
          </figure>
        </div>

<?php
// Don't Purge
// trends-2021-slideshow__cell--1 
// trends-2021-slideshow__cell--2 
// trends-2021-slideshow__cell--3
?>