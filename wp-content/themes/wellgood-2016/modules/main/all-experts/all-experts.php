<?php
/**
 * All Experts Page Template
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 12.0.0
 */


$page_id = get_the_ID();

$column_count = 3;
$featured_image = get_the_post_thumbnail_url();
$page_title = get_the_title();
$page_description = get_the_content();

$all_experts = get_terms(array(
  'hide_empty' => false,
  'taxonomy' => 'experts'
));

$recent_experts_posts = get_posts( array(
	'posts_per_page' => 4,
	'post_type'	=> 'post',
  'post_status' => 'publish',
  'order' => 'DESC',
  'tax_query' => array(
    array(
      'taxonomy' => 'experts',
      'field' => 'id',
      'terms' => array_map( function( $expert ) {
        return $expert->term_id;
      }, $all_experts),
      'operator' => 'IN'
    )
  )
) );

$featured_image_overlay = get_field( 'add_overlay_to_featured_image', $page_id );
?>


<page id="primary" class="all-experts content-area" data-module-init="all-experts">
  <div class="all-experts__header" style="background-image:url('<?= $featured_image; ?>');">
    <div class="all-experts__title"><?= $page_title; ?></div>
    <div class="all-experts__description all-experts__description--desktop"><?= $page_description; ?></div>
    <?php if ( $featured_image_overlay ) : ?>
      <div class="all-experts__header--overlay"></div>
    <?php endif; ?>
  </div>
  <?php if ( ! is_wp_error( $all_experts ) ) : ?>
    <main id="main">
      <div class="container">
        <div class="all-experts__description all-experts__description--mobile"><?= $page_description; ?></div>
        <ul class="all-experts__grid">
          <?php foreach ( $all_experts as $key => $expert ) :
            $expert_tax = $expert->taxonomy;
            $expert_id = $expert->term_id;
            $expert = get_term( $expert_id, $expert_tax );
            $expert_name = $expert->name;
            $expert_slug = $expert->slug;
            $expert_bio = $expert->description;
            $expert_title = get_field( 'expert_title', "{$expert_tax}_{$expert_id}" );
            $expert_link = get_field( 'expert_url', "{$expert_tax}_{$expert_id}" );
            $expert_author_id = get_field( 'expert_author_page', "{$expert_tax}_{$expert_id}" );
            $expert_articles = $expert_author_id ? get_author_posts_url( $expert_author_id ) : null;
            $expert_image_url = get_field( 'expert_image', "{$expert_tax}_{$expert_id}" )['sizes']['thumbnail'];
            ?>
            <li id="<?= $expert_slug; ?>" class="all-experts__card expert"> 
              <div class="expert__image">
                <?php the_module('image', array(
                  'image_src' => $expert_image_url,
                  'image_src_retina' => $expert_image_url,
                  'image_alt' => $expert_name
                )); ?>
              </div>
              <div class="expert__info">
                <a href="<?= $expert_link; ?>" target="_blank">
                  <div class="expert__name">
                    <?= $expert_name; ?>
                  </div>
                  <div class="expert__title">
                    <?= $expert_title; ?>
                  </div>
                </a>
                <div class="expert__bio">
                  <?= $expert_bio; ?>
                </div>
                <?php if ( $expert_link || $expert_articles ) : ?>
                  <div class="expert__links">
                    <?php if ( $expert_link ) : ?>
                      <a class="expert__link" href="<?= $expert_link; ?>">Visit Site</a>
                    <?php endif; ?>
                    <?php if ( $expert_articles ) : ?>
                      <a class="expert__link" href="<?= $expert_articles; ?>">Read Articles</a>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </li>
          <?php endforeach; ?>
          <?php for ( $i = 0; $i < ( ( count( $all_experts ) % $column_count !== 0 ) ? ( $column_count - ( count( $all_experts ) % $column_count ) ) : 0 ); $i++ ) : ?>
            <li class="all-experts__card expert all-experts__card--blank"></li>
          <?php endfor; ?>
        </ul>
      </div>
    </main>
  <?php endif; ?>

  <?php if ( $recent_experts_posts ) : ?>
    <section class="all-experts__recent">
      <div class="container">
        <div class="all-experts__recent-title">Articles Reviewed By Experts</div>
        <div class="all-experts__recent-posts">
          <?php foreach ( $recent_experts_posts as $post ) :
            echo include_partial( 'new-article-card', array( 
              'article' => $post,
              'include_excerpt' => TRUE,
              'class' => 'all-experts__recent-card'
            ) );
          endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</page>
