<?php

// Change block modules (only frontend)
add_filter('brrl_render_block_name', function($name){
  switch($name):
    case 'acf/text-and-image':
      return 'changemakers/changemakers-text-image';
    case 'core/paragraph':
      return 'changemakers/changemakers-paragraph';
  endswitch;
  return $name;
});

?>

<div class="container changemakers-container changemakers-content">
  <?php brrl_the_content(null, $args); ?>
</div>