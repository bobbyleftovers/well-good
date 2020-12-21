<?php

$type = $type ?? 'text';
$class = $class ?? '';
$class_label = $class_label ?? '';
$class_input = $class_input ?? '';
$label = $label ?? null;
$value = $value ?? false;
$id = $id ?? false;
$attrs = $attrs ?? false;
$placeholder = $placeholder ?? false;
$disabled = $disabled ?? false;
?>

<div class="base-input <?= $class ?>">
  <?php if($label): ?>
    <label for="<?=$id?>" class="base-input__input <?= $class_label ?>"><?= $label ?></label>
  <?php endif; ?>

  <input class="base-input__input w-full block h-e45 border-gray-75 active:border-seafoam-dark focus:border-seafoam-dark bg-white placeholder-gray-70 py-e10 px-e15 <?= $class_input ?>"
    type="<?=$type?>"
    <?= $id ? 'id="'.$id.'"':''?>
    <?= $value ? 'value="'.$value.'"':''?>
    <?= $placeholder ? 'placeholder="'.$placeholder.'"':''?>
    <?= $disabled ? 'disabled':''?>
    <?= $attrs ?>
    />

</div>
