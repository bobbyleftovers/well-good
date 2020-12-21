<?php
  $tag = $tag ?? 'button';
  $type = $type ?? 'text';
  $inputClasses = $inputClasses ?? '';
  $name = $name ?? '';
  $id = $id ?? '';
  $placeholder = $placeholder ?? '';
  $label = $label ?? $placeholder;

  $class = $class ?? '';
  $attrs = $attrs ?? '';
  $labelAttrs = $labelAttrs ?? '';
  $disabled = $disabled ?? false;
  $required = $required ?? false;

  if($disabled){
    $attrs .= ' disabled';
  }

  if ($required) {
    $attrs .= ' required';
  }

  if(!$disabled){
    $class .= ' enabled ';
  } else {
    $class .= ' disabled ';
  }

  $wrapperAttrs = $wrapperAttrs ?? '';
?>

<div class="text-input relative <?= $class?>" data-module-init="text-input" <?= $wrapperAttrs?>>
  <input class="absolute text-small <?= $inputClasses; ?> h-full top-0 left-0 w-full block" type="<?= $type; ?>" id="<?= $id ?>" <?= $attrs ?> name="<?= $name?>">
  <label <?= $labelAttrs ?> class="text-small" for="<?= $id?>"><?= $label ?? $placeholder ?></label>
</div>
