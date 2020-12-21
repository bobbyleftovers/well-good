<?php 
// $bg_image = get_the_post_thumbnail_url( $post, 'full');
$bg_image = include('background-img.php'); 
?>

<div class="trends-2021-home-hero__bg absolute z-0">
    <div class="trends-2021-home-hero__bg__default absolute z-10"></div>
    <div class="trends-2021-home-hero__bg__img absolute bg-cover bg-bottom bg-no-repeat z-10 w-1/1 h-1/1" style="background-image: url(<?= $bg_image; ?>) "></div>
  </div>