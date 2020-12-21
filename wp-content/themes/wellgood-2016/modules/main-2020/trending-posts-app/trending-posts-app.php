<?php
$title = $title ?? 'Trending';
$total = $total ?? 2;
$class = $class ?? '';
$mount = isset($mount) ? $mount : true;
$is_parsely = isset($is_parsely) ? $is_parsely : true;
$section = urlencode($section ?? 0);
$tag = urlencode($tag ?? 0);
brrl_register_vue_component('main-2020/trending-posts');
?>

<div <?php if($mount): ?>data-module-init="trending-posts-app"<?php endif; ?> class="trending-post-app <?= $class ?>">
  <!-- Title -->
  <h3 class="text-h3 mb-e19"><?=$title?></h3>

  <!-- Posts -->
  <trending-posts
    total="<?=$total?>"
    section="<?=$section?>"
    tag="<?=$tag?>"
    is_parsely="<?= $is_parsely ? 1 : 0 ?>"/>
</div>
