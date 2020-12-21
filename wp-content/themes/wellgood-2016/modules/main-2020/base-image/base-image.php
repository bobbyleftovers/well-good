<?php
$is_background = $is_background ?? false;
$is_vue = $is_vue ?? false;
$class = $class ?? '';
$src = $src ?? null;
$bg_mode = $bg_mode ?? 'bg-cover';
$bg_position = $bg_position ?? 'bg-center';
$bg_repeat = $bg_repeat ?? 'bg-no-repeat';
?>

<?php if($is_background): ?>
<div <?php /* data-module-init="base-image" */ ?>
    class="base-image <?=$bg_mode?> <?=$bg_position?> <?=$bg_repeat?> <?=$class?>"
          <?php if ($is_vue): ?>:style="{ backgroundImage: 'url(' + <?= $src ?> + ')' }"
          <?php elseif($src): ?>style="background-image: url(<?= $src ?> );"
          <?php endif; ?>>
        </div>
<?php else: ?>
  <img class="<?=$class?>" src="<?=$src?>" data-module-init="base-image" />
<?php endif; ?>
