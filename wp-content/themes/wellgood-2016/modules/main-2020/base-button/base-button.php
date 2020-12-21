<?php
  $tag = $tag ?? 'button';
  $text = $text ?? 'This is a button';
  $class = $class ?? '';
  $type = $type ?? 'primary';
  $attrs = $attrs ?? '';
  $disabled = $disabled ?? false;
  $text_class = $text_class ?? ($type == 'white' ? 'text-seafoam-dark' : 'text-white');

  if($tag === 'a' && isset($href)) $attrs .= " href='$href' ";
  if($tag === 'a' && isset($target)) $attrs .= " target='$target' ";

  if($disabled){
    $attrs .= ' disabled';
  }
  if(!$disabled){
    $class .= ' enabled ';
  } else {
    $class .= ' disabled ';
  }
?>
<<?=$tag?> class="no-underline text-link base-button base-button--<?=$type?> <?=$text_class?> <?=$class?>" <?=$attrs?>>
  <?php if(!is_feed()): ?> <span class="base-button__text"> <?php endif; ?>
      <?=$text?>
  <?php if(!is_feed()): ?> </span><?php endif; ?>
</<?=$tag?>>
