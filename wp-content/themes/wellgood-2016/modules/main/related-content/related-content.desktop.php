<?php
  $vue = !$gutenberg;
  $tag = $vue ? 'related-content' : 'div';
  $title_class = $title_class ?? 'text-seafoam-dark';
?>


<div class="related-content" 
  <?php if($vue): ?>
    data-module-init="related-content" 
    v-cloak
    <?php endif; ?>
  >
    <div class="related-content__wrapper <?php if(!$vue): ?>is-loaded<?php endif; ?>" 
        <?php if($vue): ?>
          v-cloak 
          :class="{'is-loaded':isLoaded}"
        <?php endif; ?>>
        <div class="related-content__title block mb-e12">
            <span class="inline pr-6 <?=$title_class?>"><?= $title; ?></span>
        </div>
      <<?=$tag?>
        class="related-content__links"
        <?php if($vue): ?>
          parent-article-permalink="<?=get_the_permalink()?>"
          current-title="<?= get_the_title() ?>"
          current-image="<?= $featured_image ?>"
          v-on:posts-loaded="onPostsLoaded"
          start-date="<?= $post_date->format('Y-m-d'); ?>"
          tag="div"
          inline-template url="<?= $url ?>"
          secret="<?= $secret ?>"
          apikey="<?= $apikey ?>"
          limit="<?= $limit ?>"
        <?php endif; ?>
        >
          <?php if($vue): ?>
              <div>
                <?php include 'related-content.card.php'; ?>
              </div>
            <?php
            else:
              foreach($posts as $post):
                include 'related-content.card.php';
              endforeach;
            endif; 
          ?>  
        </<?=$tag?>>
    </div>
</div>