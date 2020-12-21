<?php
/**
 * Gift template subnav
 * @author BarrelNY
 */

$page_id = $post->product_guide_subnav_sub_field ? $post->product_guide_subnav_sub_field : get_the_id();
$parent_id = get_the_parent_id($page_id);
$parent_post = get_post($parent_id);
$parent_post_title = $parent_post->post_title;
$parent_post_url = get_the_permalink( $parent_id );

$nav_items = $post->product_guide_subnav_field;
$nav_headline = get_field('navigation_headline', $parent_id);

$campaign_title = get_the_title($parent_id);
$page_title = get_the_title($page_id);

$is_index = $page_id === $parent_id;
?>

<div class="product-guide-subnav" data-module-init="product-guide-subnav">

  <?php
  if ($nav_headline) : ?>
    <h2 id="product-guide-content" class="product-guide-subnav__headline">
      <?= $nav_headline; ?>
      <span class="product-guide-nav-headline__colon">:</span>
    </h2>
  <?php
  endif; ?>

  <div class="product-guide-subnav__pages">
    <ul class="product-guide-subnav__list">
      <?php
      $nav_home_classes = array('product-guide-subnav__item');
      if ($is_index) :
        array_push($nav_home_classes, 'active');
      endif; ?>
      <li class="<?= implode(' ', $nav_home_classes); ?>" data-title="Everything" data-id="<?= $parent_id; ?>">
        <a href="<?= get_the_permalink( $parent_id ); ?>" class="load-products" data-url="<?= $parent_post_url; ?>" data-id="<?= $parent_id; ?>" data-title="Everything">
          <h4>Everything</h4>
        </a>
      </li>

      <?php
      foreach ($nav_items as $nav_item) :
        $title = $nav_item->post_title;
        $id = $nav_item->ID;
        $url = get_the_permalink( $id );

        $nav_item_classes = array('product-guide-subnav__item');
        if ($page_id == $id) :
          array_push($nav_item_classes, 'active');
        endif;
        ?>
        <li class="<?= implode(' ', $nav_item_classes); ?>" data-title="<?= $title; ?>" data-id="<?= $id; ?>">
          <a href="<?= $url; ?>" class="load-products" data-url="<?= $url; ?>" data-id="<?= $id; ?>" data-title="<?= $title; ?>">
            <h4><?= $title; ?></h4>
          </a>
        </li>
      <?php
      endforeach; ?>
    </ul>
  </div>

  <div class="product-guide-subnav__mobile-menu">
    <span>
      Show me
    </span>
    <span class="product-guide-subnav__mobile-select">
      <span id="nav-display" class="product-guide-subnav__mobile-value"><?= $is_index ? 'Everything' : $page_title; ?></span>
      <select class="product-guide-subnav--mobile" id="product-guide-page-select">

        <option class="product-guide-subnav__item" value="Everything"<?= $is_index ? ' selected="selected"' : ''; ?> data-url="<?= $parent_post_url; ?>" data-title="Everything" data-id="<?= $parent_id; ?>">
          Everything
        </option>

        <?php
        foreach ($nav_items as $nav_item) :
          $title = $nav_item->post_title;
          $nav_item_id = $nav_item->ID;
          $url = get_the_permalink( $nav_item_id );
          $nav_active = $page_id == $nav_item_id ? ' selected="selected"' : '';
          ?>
          <option class="product-guide-subnav__item" value="<?= $title; ?>" data-url="<?= $url; ?>"<?= $nav_active; ?> data-title="<?= $title; ?>" data-id="<?= $nav_item_id; ?>">
            <?= $title; ?>
          </option>
        <?php
        endforeach ;?>

      </select>
      <div class="product-guide-subnav__mobile-menu--arrow" aria-label="open category filter">
        <?= get_svg('down-icon', array(
          'role' => 'button'
        )); ?>
      </div>
    </span>
  </div>

</div>
