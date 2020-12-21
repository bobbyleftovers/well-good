<?php

if(is_object($args) && is_a($args, 'WP_Post')) $post = $args;

$is_video = $is_video ?? false;
$lazy_image = $lazy_image ?? true;
$play_inline = $play_inline ?? false;
$is_featured = $is_featured ?? false;
$is_mini = $is_mini ?? false;
$is_vue = $is_vue ?? false;
$is_dummy = $is_dummy ?? false;
$class = $class ?? '';
$post = $post ?? null;
$thumbnail_class = $thumbnail_class ?? 'bg-tan-light';
if(!isset($social_share)) $social_share = true;

if($is_featured){
  $image_size = '627x436';
  $image_crop = 'article-retina';
} else if($is_mini) {
  $image_size = '84x84';
  $image_crop = 'post-card-mini';
} else {
  $image_size = '300x200';
  $image_crop = 'article';
}

if($post){
  $title = $title ?? $post->post_title;
  $excerpt = $excerpt ?? wp_trim_words(strip_shortcodes(strip_tags($post->post_content)), 15);
  $date = $date ?? $post->post_date;
  $image = $image ?? get_the_post_thumbnail_url($post, $image_crop);
  $href = $href ?? get_the_permalink($post);
  $author = $author ?? get_the_author_meta('display_name', $post->post_author);
  $is_video = is_video($post);
}

if($is_vue){
  $title = $title ?? "{{post.title}}";
  $date = $date ?? "{{post.date}}";
  $author = $author ?? "{{post.author}}";
  $excerpt = $excerpt ?? "{{post.excerpt}}";
  $image = $image ?? "post.image";
  $href = $href ?? "#";
}

if($is_dummy){
  $title = '';
  $author = '';
  $excerpt = '';
  $date = '';
  $image = wag_get_fallback_image('url');
  $href = null;
}

if(isset($href)) $tag = 'a';
else  { $tag = 'div'; $href = false; }

$title = $title ?? 'This is a placeholder title';
$excerpt = $excerpt ?? 'This is a placeholder excerpt';
if(!isset($image)) $image = wag_get_fallback_image('url');
if(!$is_vue && !$is_dummy) $date = date('F d, Y',strtotime($date ?? 'April 30, 2020'));
if(!isset($author)) $author = false;

if($href):
  if ($is_vue): 
    $href_attr = ':href="post.url"';
  else: 
    $href_attr = "href='$href'";
  endif;
else:
  $href_attr = '';
endif;

?>
<div class="w-full relative post-card group <?= $is_featured ? 'post-card--featured':''?> <?= $is_mini ? 'post-card--mini flex':'block'?> <?= $class ?>">
  <!-- thumbnail -->
    <div class="<?= $is_mini ? '':'relative overflow-hidden' ?> <?= ($is_featured || $is_mini) ? '': 'mb-e14'?>">
      <<?=$tag ?> <?= $href_attr?> class="<?=$thumbnail_class?> block relative overflow-hidden post-card__thumbnail <?= $is_dummy ? 'bg-tan-medium': '' ?> <?= $is_mini ? 'w-e76 sm:w-e122 md:w-e77 lg:w-e84 mr-e14 sm:mr-gutter':'w-full'?>">
        <?php

          $class = 'post-card__thumbnail__bg scale-100 transform origin-center
          top-1/2 left-1/2 absolute -translate-x-1/2 -translate-y-1/2
          transition duration-500 w-full h-full
          sm:pt-e10 md:pt-0';

          if($is_vue || !$lazy_image):
            //new component - no lazy load yet
            brrl_the_module('main-2020/base-image',array(
              'src' => $image,
              'is_vue' => $is_vue,
              'is_background'=> true,
              'class' => $class
            ));

          else:
            // Legacy component - contains lazy load on scroll
            the_module('image', array(
              'image_src' => $image,
              'image_src_retina' => $image,
              'image_alt' => $title,
              'is_video' => false,
              'class' => $class
            ));
          endif;
        ?>
        <?php if($is_featured): ?>
          <div class="post-card__thumbnail-mask absolute w-full h-full"></div>
        <?php endif; ?>
        <?php if($is_video && !$is_mini): ?>
          <div class="absolute bottom-e20 right-e20">
            <?php brrl_the_module('main-2020/base-button-play', array('size' => 'xs')); ?>
          </div>
        <?php endif; ?>
        <div class="post-card__thumbnail-padding w-full"></div>
      </<?=$tag ?>>
      
      <?php
        //Share
        if($social_share && !$is_vue && !$is_mini):
          brrl_the_module('main-2020/post-card-share',array(
                'post' => $post,
                'permalink' => $href
              ));
        endif;
      ?>
    </div>

    <!-- body -->
    <<?=$tag?> <?= $href_attr?> class="post-card__body <?= $is_featured ? 'absolute bottom-0 left-0 p-e20 md:p-e22 lg:p-e30 text-white':'text-gray group-hover:text-gray-75 transition duration-500'?>">
        <div class="text-tag mb-e8 <?= $is_featured ? '':''?>">
          <?= $date ?>
        </div>
        <div class="text-h5 mb-e4">
          <?= $title ?>
        </div>
        <?php if(!$is_mini && !$is_featured): ?>
          <div class="text-small">
            <?= $excerpt ?>
          </div>
        <?php endif; ?>
        <?php if($author): ?>
        <div class="<?= $is_mini ? 'mt-e10':'mt-e12 sm:mt-e16 md:mt-e17 lg:mt-e18' ?> text-tag">
          By <?= $author ?>
        </div>
        <?php endif; ?>
    </<?=$tag?>>
</div>

