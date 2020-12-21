<?php 
use PHPHtmlParser\Dom;
$dom = new Dom;
$dom->loadStr($innerHTML);
$quote = $dom->find('blockquote')[0]->find('p')[0]->innerHtml;
if(!$quote) return;
$cite = $dom->find('cite')[0];
if($cite) $cite = $cite->innerHtml;
else $cite = false;
$text_class = apply_filters( 'core/quote:class:text', 'text-quote text-center', $attrs);
$cite_class = apply_filters( 'core/quote:cite:class', 'text-big', $attrs);
$class = apply_filters( 'core/quote:class', "core-quote relative $text_class", $attrs  );
?>

<blockquote class="<?= $class ?>">
  <?= $quote ?>
  <?php if($cite): ?>
    <cite class="<?=$cite_class?>"><?=$cite?></cite>
  <?php endif; ?>
</blockquote>