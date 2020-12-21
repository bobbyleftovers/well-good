<?php 
$align = apply_filters('wg/button:align', $align ?? 'left'); 

$args = apply_filters('wg/button:args', array(
  'text' => strip_tags($innerHTML), 
  'tag' => isset($url) ? 'a' : 'button', 
  'href' => $url ?? false,
  'type' => strpos($innerHTML, 'is-style-white') !== false ? 'white': 'primary',
  'target' => $newTab ? "_blank" : "_self",
  "disabled" => isset($url) ? false : true
))
?>

<div class="text-<?=$align?> relative mb-e15">
  <?php
  brrl_the_module('main-2020/base-button', $args);
  ?>
</div>
