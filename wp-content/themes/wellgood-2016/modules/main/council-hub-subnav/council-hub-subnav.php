<?php

    global $post;
    $current_page = $post->ID;

    $hub_id = $post->post_parent ? $post->post_parent : $current_page;
    $hub_link = $hub_id ? get_permalink( $hub_id ) : '';

    $post_args = array(
      'post_type' => 'page',
      'post_parent' => $hub_id,
      'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'menu_order'
    );
    $query = new WP_Query( $post_args );

    $sub_nav_display = get_field('display_back_button', $hub_id);
    $back_button_text = ( get_field('back_button_text') ) ? get_field('back_button_text') : get_field('back_button_text', $hub_id);
    $sub_nav_mobile_cta = get_field('mobile_menu_title', 'options');

    if( $post->post_parent && $sub_nav_display ){
        echo "<a href='$hub_link' class='chub-back'>" . ( $back_button_text ? $back_button_text : 'Back to Main Page' ) . "</a>";
    }
?>


<div class="chub-nav-wrapper js-chub-mobilenav-toggle" data-module-init="council-hub-subnav"> <!-- data-module-init="council-hub-subnav" -->
    <div class="chub-mobilenav js-chub-mobilenav-trigger">
        <h3 class="chub-mobilenav__toggle"><?= ( $sub_nav_mobile_cta ? $sub_nav_mobile_cta : 'Explore All Categories' ); ?></h3>
        <div class="chub-mobilenav__icon"></div>
    </div>
    <ul class="chub-nav">

        <?php
        while( $query->have_posts() ) : $query->the_post();

          // Menu item variables
          $id = $post->ID;
          $title = get_field( 'menu_title', $id ) ? get_field( 'menu_title', $id ) : $post->post_title;
          $url = get_the_permalink( $id );

          $menu_thumb_image = get_field( 'menu_thumbnail', $id );
          $fallback_image = get_field('image_clone')['header_mobile'] ? get_field('image_clone')['header_mobile'] : get_field('image_clone')['header'] ;
          $menu_thumb_url = $menu_thumb_image ? $menu_thumb_image['sizes']['medium'] : $fallback_image['sizes']['medium'];
          $menu_thumb_retina = $menu_thumb_image['sizes']['article-retina'] ? $menu_thumb_image['sizes']['article-retina'] : $fallback_image['sizes']['article'];

          $item_state = get_field('page_state', $id);
          $inactive_text = get_field('inactive_text', $id);

          ?>

          <li class="chub-nav__item <?= $item_state; ?>">
              <?php if( $item_state == 'active' ) : ?><a href="<?= $url ?>" data-vars-event="sub nav" class="chub-link"><?php endif; ?>
                  <div class="chub-image <?= ($id == $current_page ? 'current-page' : ''); ?>">
                      <?php
                          if( $inactive_text && $item_state == 'inactive' ){
                              echo "<span class='chub-inactive-message'>$inactive_text</span>";
                          }
                      ?>
                      <div class="chub-image__image">
                          <?php
                              the_module('image', $menu_thumb_url, $menu_thumb_retina, $title);
                          ?>
                      </div>
                  </div>
              <?php if( $item_state == 'active' ) : ?></a><?php endif; ?>

              <?php if( $item_state == 'active' ) : ?><a href="<?= $url ?>" data-vars-event="sub nav" class="chub-link"><?php endif; ?>
                  <div class="chub-title"><?= $title; ?></div>
              <?php if( $item_state == 'active' ) : ?></a><?php endif; ?>

          </li>
        <?php endwhile; wp_reset_query();?>

    </ul>
</div>
