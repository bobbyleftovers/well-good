<?php

$is_vue = $is_vue ?? false;
$is_dummy = $is_dummy ?? false;
$class = $class ?? '';

if($is_vue){
  $title = $title ?? "{{post.title}}";
  $author = $author ?? "{{post.author}}";
  $excerpt = $excerpt ?? "{{post.excerpt}}";
  $image = $image ?? "post.image";
  $href = $href ?? "#";
}

if($is_dummy){
  $title = '';
  $author = '';
  $excerpt = '';
  $image = null;
  $href = null;
}

if($href) $tag = 'a';
else $tag = 'div';

if($author) $author = 'By '.$author

?>
<<?=$tag?> <?php if($href):?><?php if ($is_vue): ?>:href="post.url"<?php else: ?>href="<?= $href; ?>"<?php endif ?><?php endif; ?> class="post-card <?= $class ?>">
  <div>
      <div class="post-card__image-wrapper">
<div class="post-card__image" <?php if ($is_vue): ?>:style="{ backgroundImage: 'url(' + <?= $image ?> + ')' }"<?php elseif($image): ?>style="background-image: url(<?= $image ?> );"<?php endif; ?>>
      </div>
      </div>
      <div class="post-card__content">
        <div class="post-card__main">
          <div class="post-card__title">
            <?php brrl_the_module('main/core-heading', array(
              'type' => 'h3',
              'title' => $title
            )); ?>
          </div>
          <div class="post-card__excerpt p1">
            <?= $excerpt ?>
          </div>
        </div>
        <div class="post-card__author">
        <?php brrl_the_module('main/core-heading', array(
            'type' => 'h5',
            'title' => $author
          )); ?>
        </div>
      </div>
  </div>
</<?=$tag?>>

