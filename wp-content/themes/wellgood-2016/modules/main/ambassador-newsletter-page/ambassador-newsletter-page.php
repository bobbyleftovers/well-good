<div class="ambassador-newsletter-page">
    <div class="ambassador-newsletter-page__image" style="background-image:url(<?=get_the_post_thumbnail_url(null, 'full')?>)"></div>
    <div class="ambassador-newsletter-page__content">
      <div class="container page__content-wrapper align-l">
        <?php brrl_the_module('core-heading', array(
           'type' => 'h1',
           'title' => get_the_title(),
           'class' => 'align-l'
        )); ?>
        <div class="ambassador-newsletter-page__lead lead align-l">
          <?= do_shortcode(strip_tags(get_the_content())); ?>
        </div>
        <?php the_module('social-media-links', [
            'menu_class' => 'sm-down:justify-center',
          ]); ?>
      </div>
    </div>
</div>
