<?php
  $class = $class ?? '';
  $attrs = $attrs ?? '';
  $size = $size ?? 'lg';
  $color_icon = $color_icon ?? 'border-seafoam-dark';
  $color_bg = $color_bg ?? 'bg-tan-light';
  $disabled = $disabled ?? false;
  if($disabled){
    $attrs .= ' disabled ';
  }
  if(!$disabled){
    $class = ' enabled focus:outline-none ';
  } else {
    $class .= ' disabled opacity-50 ';
  }
?>
<button class="base-button-play <?= $size ?> relative block outline-none <?=$class?>" <?=$attrs?>>
  <span class="base-button-play__icon w-0 h-0 absolute top-1/2 left-1/2 block <?= $color_icon ?>"></span>
  <span class="base-button-play__bg block <?= $color_bg ?> absolute top-0 left-0 w-full h-full top-1/2 left-1/2 transition duration-300"></span>
</button>
