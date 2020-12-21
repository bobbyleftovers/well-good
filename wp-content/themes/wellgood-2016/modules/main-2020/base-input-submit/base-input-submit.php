<?php
  $style = $style ?? 'primary';
  $class = $class ?? '';
  $value = $value ?? 'Submit';
  $name = $name ?? 'submit';
  $type = $type ?? 'submit';
  $attrs = $attrs ?? false;
  $disabled = $disabled ?? false;

  if($disabled){
    $attrs .= ' disabled';
  }
  if(!$disabled){
    $class .= ' enabled ';
  } else {
    $class .= ' disabled ';
  }
?>

<input class="text-link base-button base-button--<?=$style?> <?=$class?>"
  type="<?=$type?>"
  name="<?=$name?>"
  value="<?=$value?>"
  <?=$attrs?>
  />
