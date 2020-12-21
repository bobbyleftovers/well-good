<?php
if(strpos($innerHTML, '<h1')) $tag = 'h1';
elseif (strpos($innerHTML, '<h2')) $tag = 'h2';
elseif (strpos($innerHTML, '<h3')) $tag = 'h3';
elseif (strpos($innerHTML, '<h4')) $tag = 'h4';
elseif (strpos($innerHTML, '<h5')) $tag = 'h5';
elseif (strpos($innerHTML, '<h6')) $tag = 'h6';
$tag = trim(apply_filters( 'core/heading:tag', $tag ));
$block['tag'] = $tag;
$class = apply_filters( 'core/heading:class', 'next-'.$next['blockName'].' core-heading text-'.$tag, $block );
$innerHTML = trim(strip_tags($innerHTML, '<i><em><br><strong>'));
if($innerHTML == '') return;
?>

<<?=$tag?> class="<?=$class?>">
  <?=  $innerHTML ?>
</<?=$tag?>>