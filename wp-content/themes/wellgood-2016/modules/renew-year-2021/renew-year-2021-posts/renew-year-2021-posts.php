<?php
global $post;
global $week_index;
$week_index = isset($week_index) ? $week_index : 1;
$type = $week_index % 2 == 0 ? 'ellipse' : 'keyhole';
$title = strip_tags($innerBlocks[0]['innerHTML']);
$description = strip_tags($innerBlocks[1]['innerHTML']);
$is_active = isset($is_active) ? $is_active : false;

$viewboxes = [
  'keyhole' => '0 0 732 668',
  'ellipse' => '0 0 478 708'
];
$clip_paths = [
  'keyhole' => 'M730 270.5L731 667H1L2 270.5C2 172.657 32.5 135 112.5 71.4999C174.48 30.2076 253.334 0.99997 366 1C478.666 0.99997 557.52 30.2076 619.5 71.4999C699.5 135 730 172.657 730 270.5Z',
  'ellipse' => 'M477.5 354C477.5 451.668 450.772 540.068 407.584 604.036C364.395 668.007 304.782 707.5 239 707.5C173.218 707.5 113.605 668.007 70.4159 604.036C27.2283 540.068 0.5 451.668 0.5 354C0.5 256.332 27.2283 167.932 70.4159 103.964C113.605 39.9928 173.218 0.5 239 0.5C304.782 0.5 364.395 39.9928 407.584 103.964C450.772 167.932 477.5 256.332 477.5 354Z',
];?>

<div class="renew-year-2021-posts renew-year-2021-posts--<?= $type ?> renew-year-2021-posts--<?= ($is_active) ? 'active' : 'inactive' ?>" data-module-init="renew-year-2021-posts"><?php
  if($is_active){
    include 'renew-year-2021-posts.active.php';
  } else {
    include 'renew-year-2021-posts.inactive.php';
  }?>
</div><?php

// increment week in case theres another of these on the page
$week_index++;
