<?php global $post ?>
<div id="fb-root"></div>
<section class="comments" data-module-init="comments">
  <!--<div class="comments-facebook">
    <h2 class="module-heading comments-facebook__headline">Facebook Conversations</h2>
    <div class="fb-comments" data-width="100%" data-href="<?= the_permalink() ?>" data-numposts="10"></div>
  </div>-->
  <div class="comments-disqus">
    <p class="module-heading comments-disqus__headline">Comments</p>
    <div id="disqus_thread"></div>
    <script>
    var disqus_config = function () {
      this.page.url = '<?= the_permalink() ?>'; 
      this.page.identifier = '<?= the_ID() ?>'; 
    };
    </script>
  </div>
</section>
