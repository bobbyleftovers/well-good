<?php
$form_headline = ucwords( strtolower( get_field('footer_sign_up_headline', 'options') ) );
$form_tagline = get_field('footer_sign_up_tagline', 'options');
$logo_graphic = get_field('logo_graphic', 'options');
$logo_graphic_src = $logo_graphic ? $logo_graphic['sizes']['thumbnail'] : '';
?>
<footer class="footer theme-main-2020" data-module-init="footer">
	<div class="container signup-form signup-form--footer text-center pb-e20 md:pt-e30 md:pb-e10">
		<div class="mx-auto w-1/1 md:1/2">
      <h3 class="title text-h3 text-seafoam-dark mb-e10"><?= $form_headline; ?></h3>
      <div class="subtext text-big text-gray"><?= wp_strip_all_tags($form_tagline); ?></div>
      <?php brrl_the_module('main-2020/newsletter-form', array(
        'form_id' => 'footer',
        'location' => 'footer',
      )); ?>
    </div>
  </div>

  <div class="container collapse">
    <hr class="hr--grey">
  </div>

	<div class="container">
		<div class="footer__inner pt-e20 pb-e12 md:pb-e10 lg:px-e45 flex flex-wrap md:flex-no-wrap justify-between">
      <div class="made-in-ny md-down:hidden md:w-1/4">
        <div class="made-in-ny-logo">
          <?php the_module( 'image', get_template_directory_uri().'/assets/img/made-in-ny.png', get_template_directory_uri().'/assets/img/made-in-ny.png' ); ?>
        </div>
      </div>

			<?php
        wp_nav_menu( array(
          'sort_column' => 'menu_order',
          'theme_location' => 'footer',
          'container_class' => 'w-1/1 md:w-8/12 lg:w-1/2 md-down:order-2',
          'menu_class'  => 'flex flex-wrap lg:flex-no-wrap justify-center items-center',
          'link_before' => '<span class="text-link font-normal hover:text-seafoam-dark px-e10 sm:px-e19 lg:px-e12">',
          'link_after' => '</span>'
        ) );
			?>

      <div class="w-1/1 md:w-1/4 mb-e10">
        <?php the_module('social-media-links', array(
          'menu_class' => 'flex justify-center items-center md:justify-end md-down:order-1'
        )); ?>
      </div>
    </div>

    <div class="text-small text-center text—gray-70 pb-e20 md:pb-e30 footer-copyright copyright">
      <?= apply_filters('wg_copyright',strip_tags(get_field('copyright', 'options'), '<a><p>')); ?>
      <?php do_action('wg_copyright'); ?>
    </div>
	</div>


</footer>
<script>
  window.populateMarketingSlider = function (payload){
    console.log(payload)
    if (window._populateMarketingSlider) {
      window._populateMarketingSlider(payload)
    } else {
      window.__EMAIL_CAPTURE__PAYLOAD__ = payload
    }
  }
</script>
<div id='div-gpt-ad-1597160603724-0' style="top: 50%; left: 0;" class="fixed email-capture-ad" style='width: 1px; height: 3px;'></div>
<div id='div-gpt-ad-1597160477614-0' style="top: 50%; left: 0;" class="fixed email-capture-ad" style='width: 1px; height: 3px;'></div>

<?php
the_module('email-capture', array( 'form_id' => 'email-capture' ));
the_module('modal-maps');

