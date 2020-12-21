<div class="acf-post-card relative">
  <?php 
  if($post):
    brrl_the_module('main-2020/post-card', array(
      'post' => $post,
      'is_mini' => true,
      'class' => 'text-left',
      'lazy_image' => false
    )); 
  else:
    brrl_the_module('acf/placeholder', 'Select a post on the sidebar');
  endif;
  ?>
  <div class="absolute top-0 left-0 w-1/1 h-1/1 z-20"></div>
</div>