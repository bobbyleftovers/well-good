<?php
  $title = $title ?? false;
  $class = $class ?? '';
  $inline_ads = $inline_ads ?? false;
  $max = $max ?? 999;
  $i = 0;
  global $post;
  $prev_post = $post;
  if(!isset($social_share)) $social_share = true;
  $post->social_share = $social_share;
?>
<?php if(have_posts()): ?>
  <div class="container <?= $class ?>">
    <?php if($title): ?>
      <h2 class="text-h2 mb-e25"><?=$title?></h2>
    <?php endif;?>
    <div class="flex flex-wrap -mx-gutter1/2">
        <?php while ( have_posts() ) : the_post(); if($i >= $max) break; ?>
            <div class="px-gutter1/2 w-full sm:w-1/2 ml:w-1/4 mb-e45">
              <?php
                global $post;
                $post->social_share = $social_share;
                brrl_the_module('main-2020/post-card', $post);
              ?>
            </div>

            <?php 
            if ( $i != 0 && ($i+1) % 12 === 0 ) :
              if ( $inline_ads && $inline_ads['index'] < $inline_ads['max'] ) :
                the_module( 'advertisement', array(
                  'slots' => array(
                    'inline',
                    'slot'
                  ),
                  'page' => 0,
                  'iteration' => $inline_ads['index']
                ) ); $inline_ads['index']++;
              endif;
            endif;
            ?>

        <?php $i++; endwhile; ?>
      </div>
  </div>
<?php global $post; $post = $prev_post; endif; ?>

<?php /* brrl_the_module('main-2020/posts-grid-more'); */ ?>
