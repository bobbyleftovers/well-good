<?php if(have_rows('franchise_slideshow')): ?>
<div class="franchises-slideshow">
  <div>
    <div class="container">

            <?php brrl_the_module('main/core-heading', array(
              'type' => 'h2',
              'title' => get_field('franchise_title') ?? __('Well+Good Franchises')
            )); ?>

          <div class="franchises-slideshow__flickity row" data-module-init="franchises-slideshow">
            <?php while( the_repeater_field('franchise_slideshow') ): ?>
              <?php
                $link = get_sub_field('url');
                $size = 'apple_news_ca_portrait_4_0';
                $image = get_sub_field('background_image')['sizes'];
              ?>
              <div class="franchises-slideshow__slide col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="franchises-slideshow__slide__padding"></div>
                <div class="franchises-slideshow__slide__content-wrapper col-xs-12">
                  <a class="franchises-slideshow__slide__content"
                    target="<?=$link['target']?>"
                    title="<?=$link['title']?>"
                    alt="<?=$link['title']?>"
                    href="<?=$link['url']?>"
                    style="background-image:url(<?= $image[$size] ?>)" width="<?= $image[$size.'-width'] ?>" height="<?= $image[$size.'-height'] ?>">
                    <img src="<?php the_sub_field('logo'); ?>" />
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
  </div>
</div>
<?php endif; ?>
