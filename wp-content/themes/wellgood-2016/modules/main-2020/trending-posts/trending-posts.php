<div class="related-content__links" data-module-init="trending-posts">
  <div>

    <!-- WIP: dummy content (before is loaded) -->
    <div v-if="!posts.length" class="grid grid-cols-1 md:grid-cols-2 col-gap-e24 sm:col-gap-gutter row-gap-gutter">
      <div v-for="n in max" class="pb-e24  sm:pb-0">
        <?php brrl_the_module('main-2020/post-card', array(
          'is_dummy' => true,
          'is_mini' => true,
        )); ?>
      </div>
    </div>

    <!-- content -->
    <div class="grid grid-cols-1 md:grid-cols-2 col-gap-e24 sm:col-gap-gutter row-gap-gutter" v-else>
        <div class="border-b last:border-b-0 sm:border-b-0 border-tan-medium pb-e24 sm:pb-0" v-for="(post, key) in posts" :key="post.key">
            <?php brrl_the_module('main-2020/post-card', array(
              'is_vue' => true,
              'is_mini' => true,
              'image' => 'post.image_url'
            )); ?>
        </div>
    </div>

  </div>
</div>
