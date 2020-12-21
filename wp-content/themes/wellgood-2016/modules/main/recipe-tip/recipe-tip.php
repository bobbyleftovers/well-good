<?php

$tip_text = isset( $post->recipe_tip_field ) ? $post->recipe_tip_field : get_field('tip_text');
$tip_type = isset( $post->recipe_tip_field ) ? '--content' : '--header';
$hide_tip = get_field('tip_toggle');
$content = get_field('tip_content');
$tip_title = $content == 'cook_time' ? 'Total Time' : 'Tip';

if( $tip_text && !$hide_tip ) :
?>

<div class="post__recipe-tip<?= $tip_type ?> type-<?= $content ?>">
  <div class="recipe-tip__title-wrap recipe-tip__title-wrap--<?= $content ?>">
    <h5 class="recipe-tip__title recipe-tip__title--<?= $content ?>"><?= $tip_title ?></h5>
  </div>
  <p class="recipe-tip__text-<?= $content ?>"><?= $tip_text ?></p>
</div>
<?php endif; ?>
