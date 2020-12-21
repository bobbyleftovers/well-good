<?php
  global $post;
  $is_summer_v2 = get_field('is_v2') === true;
  $nav_items = $post->summer_subnav_field;
  $is_parent = get_the_parent_id() === 0;
  $parent_id = !$is_parent ? get_the_parent_id() : get_the_ID();
  $navigation_headline = get_field('navigation_headline', $parent_id);

  $navlist_classes = '';
  $navlist_item_classes = 'text-h3 text-white';

  if ($is_summer_v2) {
    $navlist_item_classes = 'text-h4 text-seafoam-dark';
    $navlist_classes = 'ml:flex ml:items-center ml:justify-center';
  }

if ($navigation_headline && !$is_summer_v2) : ?>
  <h2 id="summer-content" class="summer-subnav-headline text-h2"><?= $navigation_headline; ?><span class="summer-nav-headline__colon">:</span></h2>
<?php endif; ?>
<?php if(empty($navigation_headline) || $is_summer_v2):?>
  <div id="summer-content"></div>
<?php endif; ?>
<ul class="summer-subnav <?= $navlist_classes ?>" data-module-init="summer-load-articles">
  <li
    class="summer-subnav__item summer-subnav__item--everything <?= $navlist_item_classes ?> js-summer-subnav<?= (get_the_id() === $parent_id ? ' active' : ''); ?>"
    <?php if(empty($nav_items)):?>
    style="display: none;"
    <?php endif; ?>
    data-title="99 Days of Summer"
    data-url="<?= get_the_permalink( $parent_id ); ?>"
    data-id="<?= $parent_id; ?>"
    data-gradient="<?= $is_summer_v2 ? 'background-image: none; background-color:' . get_field('base_background_color', $parent_id) : get_gradient($parent_id); ?>">Everything</li>

  <?php
    if(!empty($nav_items)):
    foreach ($nav_items as $nav_item) :
      $title = $nav_item->post_title;
      $url = get_the_permalink( $nav_item->ID );
      $gradient = $is_summer_v2 ? 'background-image: none; background-color:' . get_field('base_background_color', $nav_item->ID) : get_gradient($nav_item->ID);;
    ?>
    <li
      class="summer-subnav__item summer-subnav__item--<?= $title; ?> <?= $navlist_item_classes ?> js-summer-subnav<?= (get_the_id() === $nav_item->ID ? ' active' : ''); ?>"
      data-title="<?= $title; ?>"
      data-url="<?= $url; ?>"
      data-gradient="<?= $gradient ?>"
      data-id="<?= $nav_item->ID; ?>"><?= $title; ?></li>
  <?php endforeach ;?>
  <?php endif; ?>
</ul>
<div
  <?php if(empty($nav_items)):?>
  style="display: none;"
  <?php endif; ?>
  class="summer-subnav__mobile-container">
  <div class="summer-subnav__mobile-arrow" aria-label="open category filter">
    <?= get_svg('down-icon', array(
      'role' => 'button'
    )); ?>
  </div>
  <span class="summer-subnav__mobile-value <?= $is_summer_v2 ? 'text-h4' : 'text-h2' ?>"><?= ($parent_id === get_the_id() ? 'Everything' : get_the_title()); ?></span>
  <select class="summer-subnav--mobile <?= $is_summer_v2 ? 'text-h4' : 'text-h2' ?>" id="js-mobile-summer-subnav">
    <option
        class="summer-subnav__item summer-subnav__item--everything h1 js-summer-subnav"
        value="Everything"
        data-title="99 Days of Summer"
        data-url="<?= get_the_permalink( $parent_id ); ?>"
        data-id="<?= $parent_id; ?>"
        <?= ($parent_id === get_the_id() ? 'selected="selected"' : ''); ?>>Everything</option>
      <?php foreach ($nav_items as $nav_item) :
          $title = $nav_item->post_title;
          $url = get_the_permalink( $nav_item->ID );
        ?>
      <option
        class="summer-subnav__item summer-subnav__item--<?= $title; ?> h1 js-summer-subnav"
        value="<?= $title; ?>"
        data-title="<?= $title; ?>"
        data-url="<?= $url; ?>"
        data-id="<?= $nav_item->ID; ?>"
        <?= ($nav_item->ID === get_the_id() ? 'selected="selected"' : ''); ?>><?= $title; ?></option>
    <?php endforeach ;?>
  </select>
</div>
