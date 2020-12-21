<?php
global $post;

$id = $post->recipe_content_field['post_id'];

$infinite_instance = $post->post_content_sub_field ?? 0;
$post = get_post( $id );
setup_postdata( $post );

$is_branded = get_field('sponsored');
$brand = get_field('brand_details');
$fallback_img = wag_get_fallback_image();

// Post variables - redundant to post.php until a module is made for post info
$categories = get_the_category();
$post_categories = list_parent_categories($categories);
$post_category_name = $post_categories ? esc_html( $post_categories[0]->cat_name ) : false;
$post_category_link = $post_categories ? esc_url( get_category_link( $post_categories[0]->term_id ) ) : false;
$post_author_id	 = get_the_author_meta('ID');
$post_author_name   = get_the_author();
$post_author_avatar = get_avatar( $post_author_id, 32 );
$post_author_link   = $post_author_id ? esc_url( get_author_posts_url( $post_author_id ) ) : false;
$post_show_author_link = get_field('show_author', "user_{$post_author_id}");

$is_ajax = wag_post_is_ajax();

$datalayer_data = json_encode(
  get_datalayer_data(
    $id,
    'standard',
    !$is_ajax,
    $infinite_instance
  ), JSON_HEX_APOS
);
$permutive_data = json_encode(
  get_permutive_data(
    $id,
    'standard',
    !$is_ajax,
    $infinite_instance
  ), JSON_HEX_APOS|JSON_UNESCAPED_SLASHES
);

?>

<?php if ( $is_ajax ) :
  //echo get_advertising_sra($id, $infinite_instance); ?>
  <div class="infinite__ad-a">
    <?php the_module('advertisement', array(
			'slots' => array(
				'horizontal',
				'slot'
			),
			'page' => 0,
			'iteration' => 0
		)); ?>
  </div>
<?php endif; ?>

<article class="post post--legacy<?= $is_branded ? ' recipe--sponsored' : ' recipe--standard' ?><?= $is_ajax ? ' infinite' : ''; ?>" id="post-<?= $id ?>" data-url="<?= get_the_permalink(); ?>" data-datalayer='<?= $datalayer_data; ?>' data-permutive='<?= $permutive_data; ?>' data-title="<?= get_the_title(); ?>">
  <?php if( $is_branded ) : the_module( 'recipe-header-image', $is_branded ); endif; ?>
	<div class="container post__inner">

    <?php the_module( 'post-share', array(
      'new_version' => true,
      'circle' => true
    ) ); ?>

    <section class="main">

			<section class="post-section">
        <div class="post__content wg__inline-ad-wrapper">
          <header class="post__header--recipe">

            <?php
              if( $is_branded ) :
                $tagType = 'div';
                if (!empty($brand['link'])) {
                  $tagType = 'a';
                }
            ?>
              <<?= $tagType; ?> <?php if (!empty($brand['link'])): ?>href="<?= $brand['link'];?>"<?php endif;?> class="recipe-sponsor" target="_blank">
                <img class="recipe-sponsor__logo" src="<?= $brand['logo'] ? $brand['logo'] : $fallback_img['url']; ?>" alt="<?= $brand['name'] ?> Logo">
                <p class="recipe-sponsor__name">Sponsored by <?= $brand['name'] ?></p>
              </<?=$tagType;?>>
            <?php endif; ?>

            <h1 class="post__title"><?= wp_strip_all_tags(get_the_title()) ?></h1>
            <?php // print_filters_for( 'the_content' ); ?>
            <?php if( !$is_branded ) : ?>
              <?php // -MODULE-: make post-info module and adjust to account for recipe type ?>
              <div class="post__info info">
                <div class="post__info-inner">
                  <?php if ( $post_author_avatar ): ?>
                    <p class="avatar-wrapper post__avatar">
                      <?php if ( $post_show_author_link ): ?><a href="<?= $post_author_link; ?>"><?php endif; ?><?= $post_author_avatar; ?><?php if ( $post_show_author_link ): ?></a><?php endif; ?>
                    </p>
                  <?php endif; ?>

                  <?php if ( $post_categories && $post_category_name != 'Uncategorized' ): ?>
                    <p class="meta post__category"><a
                        href="<?= $post_category_link; ?>"><?= $post_category_name; ?></a></p>
                  <?php endif; ?>

                  <p class="meta by post__by"><?php if ( $post_show_author_link ): ?><a href="<?= $post_author_link; ?>"><?php endif; ?><span
                      class="icon-by"><span class="label">by</span></span>
                    <?= $post_author_name; ?><?php if ( $post_show_author_link ): ?></a><?php endif; ?><?php if ( get_field( '_postextra_hide_date' ) != 'yes' ): ?>, <?php the_time( 'F j, Y' ) ?><?php endif; ?></p>
                </div>
              </div>
              <?php the_module( 'recipe-header-image', $is_branded ); ?>
              <?php else: ?>
                <?php the_module( 'recipe-tip' ); ?>
                <hr class="hr__under-info" />
              <?php endif; ?>

          </header>
          <?php the_module( 'post-share', array(
            'mobile' => true,
            'new_version' => true,
            'circle' => false
          ) ); ?>

          <main class="recipe__main">
            <?php
            $content = apply_filters('the_content', $post->post_content);
            echo $content;
            the_module('recipe-card', $is_branded, $fallback_img); ?>

            <?php if ( get_field( 'post_tracking_code' ) ) : ?>
              <div class="no-pin">
                <?= prepare_timestamp_tracking_code( get_field( 'post_tracking_code' ) ); ?>
              </div>
            <?php endif; ?>
          </main>
        </div>
      </section>
      <section class="sidebar">
        <?php
        the_module('advertisement', array(
          'slots' => 'rightrail',
          'page' => $infinite_instance,
          'iteration' => 0,
          'sticky' => true
        )); ?>
      </section>
    </section>
		<div class="container container--with-aside">
			<footer class="post__footer">
        <?php
          echo do_shortcode('[shoplinks]');
          if( $is_branded ):
            the_module('related-posts', 'recipe--branded');
          endif;
          the_module('post-tags');
        ?>
			</footer>
			<aside class="sidebar post__sidebar">
			</aside>
		</div>
	</div>
</article>

<?php wp_reset_postdata(); ?>
