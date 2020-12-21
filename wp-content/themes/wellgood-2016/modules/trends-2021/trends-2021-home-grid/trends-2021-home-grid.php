<?php
$newsletter_card = $newsletter_card ?? false;
?>
<div class="pb-e0 sm:pb-e20 lg:pb-e40 relative z-10 wg__inline-ad-wrapper">
  <div class="trends-2021-home-grid container">
    <div class="grid 
      sm:grid-cols-2 
      sm:col-gap-e50 ml:col-gap-e65 lg:col-gap-e130 
      row-gap-e0
      "><?php
      $index = 0;
      if ($children->have_posts()):
        foreach ($children->posts as $child):
          ?>
          <div class="mb-e55 lg:mb-e70">
            <?php
            brrl_the_module('trends-2021/trends-2021-post-card', array(
              'post' => $child,
              'width' => 550,
              'top_quality' => 85,
              'format' => 'grid',
              'preload' => $index <= 3 ? true : false
            ));
            ?>
          </div>
          <?php
          $index++;
          endforeach;
          
          if(sizeof($children->posts ) % 2 != 0):
          ?>
          <div class="mb-e55 lg:mb-e70">
            <div class="trends-2021-home-grid__post-card text-white bg-seafoam-dark relative bg-center bg-cover bg-no-repeat"
              <?php if($newsletter_card && $newsletter_card['image']): ?>
                  style="background-image: url(<?= wg_resize($newsletter_card['image']['url'], 445, 552, true, 65); ?>)"
              <?php endif; ?>
            >
              <div class="trends-2021-home-grid__post-card__padding w-1/1"></div>
              <div class="trends-2021-home-grid__post-card__content absolute left-0 w-1/1 transform top-1/2 -translate-y-1/2">
                <div class="">
                  <?php if($newsletter_card && $newsletter_card['text']): ?>
                    <div class="text-h2 text-white text-center mb-e20 ml:mb-e30 lg:mb-e40">
                      <?=strip_tags($newsletter_card['text'],'<i><em>')?>
                    </div>
                  <?php endif; ?>
                  <?php if(!$newsletter_card || $newsletter_card['type'] !== 'cta'):
                      brrl_the_module('main-2020/newsletter-form');
                    elseif($newsletter_card && $newsletter_card['cta']): ?>
                    <div class="text-center pb-e20 lg:pb-e10">
                    <?php  brrl_the_module('main-2020/base-button', array(
                        'text' => $newsletter_card['cta']['title'],
                        'href' => $newsletter_card['cta']['url'],
                        'target' => $newsletter_card['cta']['target'],
                        'tag' => 'a',
                        'type' => 'white',
                      ));
                    ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <?php
          endif;

      endif;?>
    </div>
  </div>
  <?php brrl_the_module('acf/advertisement'); ?>
</div>