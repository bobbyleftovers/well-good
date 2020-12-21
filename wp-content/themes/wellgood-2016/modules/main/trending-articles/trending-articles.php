<?php
$fetch_parsely = $fetch_parsely ?? (get_field('trending_articles_fetch_parsely_global', 'option') ?? true) && ( get_field('trending_articles_fetch_parsely') ?? true );
if (isset($title) && $title == '') $title = null;
$local_title = get_field('trending_articles_title');
if($local_title == '') $local_title = null;
$title = $title ?? $local_title?? (get_field('trending_articles_title_global', 'option') ?? __('Trending Articles'));
if($title == '') $title = __('Trending Articles');
?>

<div data-module-init="trending-articles" v-cloak>
  <div class="trending-articles container">

          <?php brrl_the_module('main/core-heading', array(
            'type' => 'h2',
            'title' =>  $title
          )); ?>

          <trending-articles :fetch_parsely="<?= $fetch_parsely ? '1':'0' ?>" class="related-content__links" tag="div" inline-template>
              <div class="trending-articles__row row" v-if="!posts.length">
                  <div class="trending-articles__col col-12 col-sm-6 col-md-4 is-dummy" v-for="n in 4">
                      <?php brrl_the_module('post-card', array(
                        'is_dummy' => true
                        )); ?>
                  </div>
              </div>
              <div class="trending-articles__row row" v-else>
                  <div class="trending-articles__col col-12 col-sm-6 col-md-4" v-for="(post, key) in posts" :key="post.key">
                      <?php brrl_the_module('post-card', array(
                        'is_vue' => true,
                        'class' => 'trending-articles__post',
                        'image' => 'post.image_url'
                        )); ?>
                  </div>
              </div>
          </trending-articles>
      </div>
</div>
