<?php
$class = $class ?? '';
$text = $text ?? $args;
$start = substr($text, 0, 3);
if($start == '<p>') {
  $cap = substr($text, 3, 1);
  $text = substr($text, 4);
}
else {
  $start = '';
  $cap = substr($text, 0, 1);
  $text = substr($text, 1);
}

?>
<div class="drop-cap text-big text-gray">
  <?= $start ?><span class="drop-cap__first text-dropcap <?= $class ?>"><?= $cap ?></span><?= $text ?>
</div>
