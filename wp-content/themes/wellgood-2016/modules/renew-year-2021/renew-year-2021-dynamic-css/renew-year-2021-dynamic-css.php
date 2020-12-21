<style>
  <?php 
  // Css utilities (all children pages)
  foreach($bg_colors as $postId => $color): ?>
    .bg-<?=$postId?> { 
      background-color: <?=$color?>; 
    }
  <?php endforeach;  
  
  // Css vars (current child page)
  if(!$is_parent): ?>
    :root {
      --renew-year-2021-bg: <?=$bg_colors[$post->ID]?>;
    } 
  <?php endif; ?>
</style>