<?php
  $title = $title ?? false;
  $footer = $footer ?? false;
?>
<div data-module-init="code" class="mb-e10 mt-e10">
  <?php if($title): ?>
    <div class="font-code text-small-mobile"><?= $title ?></div>
  <?php endif; ?>
  <?php if($lang === 'html'): ?>
    <script type="text/plain" class="language-markup"><?= $code ?></script>
  <?php else: ?>
    <pre class=""><code class="language-<?= $lang ?> text-sm"><?= $code ?></code></pre>
  <?php endif; ?>
  <?php if($footer): ?>
    <div class="font-code" style="font-size: 12px; line-height: 1.6em;"><?= $footer ?></div>
  <?php endif; ?>
</div>
