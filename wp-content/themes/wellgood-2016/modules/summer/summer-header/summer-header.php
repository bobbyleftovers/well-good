<?php
$parent_id = get_the_parent_id() == 0 ? get_the_ID() :  get_the_parent_id();
$is_summer_v2 = get_field('is_v2', $parent_id) === true;

$title = get_field('title') ? get_field('title', $parent_id) : get_the_title($parent_id);
$tagline = get_field('tagline', $parent_id);
$hero_image = get_field('hero_image', $parent_id);
$background_photo = get_the_post_thumbnail_url($parent_id);
if ($hero_image) {
  $background_photo = $hero_image['url'];
}
$header_border_photo = get_field('header_border_photo', $parent_id)['url'];
$menu_logo = get_field('menu_logo', $parent_id)['url'];
$back_to_site_text = get_field('back_to_site_text', $parent_id);
$back_to_site_link = get_field('back_to_site_link', $parent_id);
$header_theme = get_field('header_theme', $parent_id);
?>

<?php // Element closed in summer-posts.php ?>
<?php if ($is_summer_v2) {
  the_module('header');
} ?>
<div class="summer-wrapper theme-main-2020 <?= $is_summer_v2 ? 'summer-hub-v2' : '' ?>" style="<?= $is_summer_v2 ? 'background-image: none; background-color:' . get_field('base_background_color', $post->ID) : get_gradient($post->ID); ?>">
  <header class="summer-header<?= !$is_summer_v2 && $header_border_photo ? ' summer-header--has-border' : '' ?>" data-module-init="summer-header">
    <?php if (!$is_summer_v2): ?>
    <div class="summer-header__content summer-header__wrap">
      <nav class="summer-header__brand">
        <div class="summer-header__logo"><?php the_module( 'image', $menu_logo ); ?></div>
        <a class="summer-header__backlink" href="<?= $back_to_site_link ?>">
          <?= $back_to_site_text ?><?= get_svg('back-to-WG-arrow', array(
            'role' => 'button'
          )); ?>
        </a>
      </nav>

      <div class="summer-header__banner">
        <h1 class="summer-header__title text-white">
          <?= $title ?>
        </h1>
        <div class="summer-tagline">
          <p class="summer-tagline__text max-w-e800 mx-auto mb-e30"><?= $tagline ?></p>
          <a href="#summer-content" class="js-scroll-to summer-tagline__scroll-icon" aria-label="scroll down button">
            <?= get_svg('scroll-down-icon', array(
              'role' => 'button',
              'class' => 'mx-auto'
            )); ?>
          </a>
        </div>
      </div>

    </div>

    <div class="summer-header__images">
      <div class="summer-header__image--background">
        <?php the_module('image', $background_photo); ?>
      </div>
      <?php if( $header_border_photo ) : ?>
        <div class="summer-header__image--border">
          <?php the_module('image', $header_border_photo); ?>
        </div>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="summer-header-v2-wrap flex flex-wrap md:flex-no-wrap items-center w-full <?= $header_theme == 'light' ? 'text-white' : 'text-gray-dark' ?>">
      <div class="summer-header-v2__bg hidden md:block absolute inset-0">
        <?php the_module('image', $background_photo); ?>
      </div>

      <?php
        $mobile_hero_image = get_field('mobile_hero_image', $parent_id);
      ?>
      <?php if($mobile_hero_image): ?>
        <div class="summer-header-v2__bg w-1/1 md:hidden relative ratio ro-110">
          <?php the_module('image', $mobile_hero_image['url']); ?>
        </div>
      <?php endif;?>
      <div class="w-1/1 md:w-1/2 relative"></div>
      <div class="w-1/1 md:w-1/2 summer-header-v2__contents relative z-10 -mt-e45 md:mt-0">
        <div class="summer-header-v2__contents-main mb-e60 sm:mb-0 sm:py-e85 md:py-e140">
          <?php
            $title_logo = get_field('title_logo', $parent_id);
          ?>
          <?php if($title_logo): ?>
            <div class="relative summer-header-v2__title-logo mb-e15 max-w-e167 md:max-w-e397 mx-auto">
              <img src="<?= $title_logo['url']?>" alt="<?= $title_logo['alt']?>">
            </div>
          <?php endif;?>
          <p class="px-e30 summer-tagline__text max-w-e397 mx-auto mt-0 text-big"><?= $tagline ?></p>
        </div>
      </div>
    </div>
    <a href="#summer-content" class="js-scroll-to summer-tagline__scroll-icon sm:mb-e30 absolute bottom-0 left-0 right-0" aria-label="scroll down button">
      <?= get_svg('scroll-down-icon', array(
        'role' => 'button',
        'class' => 'mx-auto'
      )); ?>
    </a>
    <?php endif; ?>
  </header>
