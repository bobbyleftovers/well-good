<?php
if(!$vue):
  if(gettype($post) === 'object'):
    $title = $post->post_title;
    $img = get_the_post_thumbnail_url($post,'medium');
    if($img) $img = wg_resize( $img, 244, 320, true, 70 );
    $href = get_the_permalink($post->ID);
  else:
    $title = '';
    $img = false;
    $href = '#';
  endif;
  if(isset($is_editor) && $is_editor) $href = false;
endif;
?>

<div class="related-content__card mb-e10 sm:mb-e20" 
          <?php if($vue): ?>
            :class="{'related-content__card--full':posts.length === 1}" 
            v-for="(post, key) in posts"
          <?php endif; ?>
        >
                <!-- Image -->
                <a 
                  <?php if($vue): ?>
                    v-on:click.prevent="trackLinkGA($event, key)" 
                    :href="post.url"
                  <?php elseif($href): ?>
                    href="<?=$href?>"
                  <?php endif; ?> 
                  class="related-content__link">
                  <?php if($vue): ?>
                    <div class="related-content__card--image bg-tan" 
                        :style="{ backgroundImage: 'url(' + post.image_url + ')' }">
                        <img :src="post.image_url" :alt="post.title" />
                    </div>
                  <?php else: ?>
                    <div class="related-content__card--image bg-tan">
                        <?php brrl_the_module('main-2020/base-image-placeholder');?>
                        <div class="absolute top-0 left-0 w-1/1 h-1/1  bg-cover bg-no-repeat bg-center" <?php if($img): ?>style="background-image:url(<?=$img?>)"<?php endif; ?>></div>
                        <div class="related-content__card--image__padding"></div>
                        <?php if($img): ?><img src="<?=$img?>" alt="<?=$title?>" /><?php endif; ?>
                    </div>
                  <?php endif; ?>
                </a>

                <!-- Text -->
                <div class="related-content__card--content">
                  <a <?php if($vue): ?>
                      v-on:click.prevent="trackLinkGA($event, key)" 
                      :href="post.url"
                    <?php elseif($href): ?>
                      href="<?=$href?>" 
                    <?php endif; ?> 
                    class="related-content__link">
                      <div class="related-content__card--title text-h5">
                        <?php if($vue): ?>
                          {{ truncate(post.title, 12) }}
                        <?php else: ?> 
                          <?= wp_trim_words($title, 10, '...') ?>
                        <?php endif; ?> 
                      </div>
                  </a>
                </div>
            </div>