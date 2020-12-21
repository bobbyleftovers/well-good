<?php
$is_vue = $is_vue ?? false;
$module = 'data-module="base-image"';
if (!empty($cover)) {
  $class .= ' image--cover';
}
if (!empty($contain)) {
  $class .= ' image--contain';
}
if (!empty($top)) {
  $class .= ' image--top';
}
if (empty($sizes)) {
  $sizes = '';
}
if (empty($attributes)) {
  $attributes = '';
}
if (!isset($use_srcset)) {
  $use_srcset = true;
}
?>
<figure class="js-wrap image <?= $class ?>" <?= $module; ?> <?= $attributes; ?>>
  <?php
    if (!empty($image)) {
      the_lazy_img($image, $size, 'image__img', $sizes, $alt, $use_srcset, $is_vue);
    }

    if (!empty($content)) {
      echo $content;
    }
  ?>
</figure>
