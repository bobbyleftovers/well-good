<?php

$type = $type ?? 'h3';
$tag = $tag ?? $type;
$class = $class ?? '';
if($type == 'h2') $class .= ' module-heading ';
?>

<<?=$tag?> class="<?=$class?> core-heading core-heading--<?=$type?>">
  <?=$title?>
</<?=$tag?>>
