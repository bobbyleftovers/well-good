<?php 
$innerHTML = preg_replace('!^<p>(.*?)</p>$!i', '$1', trim($innerHTML));
?>

<p class="<?= apply_filters( 'core/paragraph:class', 'core-paragraph', $block ) ?>">
  <?= $innerHTML ?>
</p>