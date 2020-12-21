<?php
  $parent_id = $post->summer_posts_sub_sub_sub_field['parent_id'];
  $is_summer_v2 = get_field('is_v2', $parent_id) === true;
  $posts = $post->summer_posts_field;
  $iterator = $post->summer_posts_sub_field;
  $videos = isset($post->summer_posts_sub_sub_field) ? $post->summer_posts_sub_sub_field : null;
  $page_id = $post->summer_posts_sub_sub_sub_field['id'];
  $ad_unit_iterations = $post->summer_posts_sub_sub_sub_field['ad_units'];
  $ad_spaces = array();
  $ad_spaces_prototype = array(
    1 => array(
      'top' => 'b',
      'bottom' => 'c'
    ),
    2 => array(
      'top' => 'd',
      'bottom' => 'e',
    ),
    3 => array(
      'top' => 'f',
      'bottom' => 'g'
    )
  );

  for( $i = 0; $i < $ad_unit_iterations; $i++ ) {
    $ad_spaces = array_merge($ad_spaces, $ad_spaces_prototype );
  }
?>

<section class="summer-articles">
  <?php
  $ii = 0;
  $ad_iterator = $iterator - 1; // Account for $ad_spaces starting at index 0
  foreach ($posts as $post) :
    if( $post ):
    $id = $post->ID;
    $title = $post->post_title;
    $featured_image = get_the_post_thumbnail_url($id);
    $link = get_the_permalink($id);
    $excerpt = get_the_excerpt($id);
    $branded = $is_summer_v2 && article_is_branded($id);
    ?>

    <?php if ($ii === 0 || $ii === 2 || $ii === 3 ) : ?>
      <div class="article-row article-row--<?= $ii; ?>">
    <?php endif; ?>
      <article class="summer-article waypoint" data-padding="100">
        <a href="<?= $link; ?>" class="summer-article__image">
          <?php the_module('image', $featured_image, $featured_image); ?>
          <?php if ($ii === 2) : ?>
            <h3 class="summer-article__title summer-article__title--inner text-h2"><?= $title; ?></h3>
          <?php endif; ?>
        </a>
        <div class="summer-article-meta">
          <?php if ( $branded ) :
            $branded_container = array( 'text-label text-seafoam-dark' );
            switch (true) :
              case $ii <= 1 :
                $branded_container[] = 'text-center';
                $branded_container[] = '-mb-e10';
                $branded_container[] = 'mt-e20';
                break;

              case $ii > 2 :
                $branded_container[] = 'text-center';
                $branded_container[] = '-mb-e10';
                $branded_container[] = 'mt-e20';
                break;

              case $ii === 2 :
                $branded_container[] = 'mb-e10';
                break;

            endswitch;
            ?>
            <div class="<?= implode( ' ', $branded_container ); ?>">
              Paid Content
            </div>
          <?php endif; ?>
          <a href="<?= $link; ?>">
            <?php if ($ii <= 1) : ?>
              <h3 class="summer-article__title text-h5 md:text-h3 w-full mt-0"><?= $title; ?></h3>
            <?php endif; ?>
            <?php if ($ii > 2) : ?>
            <h3 class="summer-article__title text-h5 md:text-h3 mt-0"><?= $title; ?></h3>
            <?php endif; ?>
            <?php if ($ii === 2) : ?>
              <h3 class="summer-article__title summer-article__title--outer <?= $is_summer_v2 ? 'text-h5 md:text-h3' : 'text-h1 md:text-h2 lg:text-h1' ?>"><?= $title; ?></h3>
              <p class="summer-article__excerpt <?= $is_summer_v2 ? 'text-default' : 'text-big' ?>"><?= $excerpt; ?></p>
            <?php endif; ?>
          </a>
        </div>
      </article>
    <?php if ($ii === 1 || $ii === 2 || $ii === 5) : ?>
      </div>
    <?php endif;
    $ii++;
    endif;
  endforeach; ?>
</section>

<?php if ($iterator === 1 && $videos) :
  the_module('summer/summer-videos', $videos);
endif; ?>
