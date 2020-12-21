<?php

// Ignore purge: ml:order-0 ml:order-1 ml:order-2 ml:order-3 ml:order-4 ml:order-5 ml:order-6 ml:order-7 ml:order-8 ml:order-9

// Vars
include 'injections-positions-by-length.php';

$not_first_iteration = $not_first_iteration ?? false;
$grid_index = $grid_index ?? 0;
$injections = $injections ?? array();
$injections_reversed = array_reverse($injections);
$injection = array_pop($injections_reversed);
$posts = $posts ?? array();
$length = $injection ? sizeof($posts)+1 : sizeof($posts);
$next = false;
$no_featured = $no_featured ?? false;
$is_mini = $is_mini ?? false;
$col_class = $col_class ?? "sm:w-1/2 ml:w-1/4";
$post_card_class = $post_card_class ?? '';
$wrapper_class = $wrapper_class ?? '';
if(!isset($social_share)) $social_share = true;

// Has featured?
if(!function_exists('switch_featured')){
  function switch_featured(){
    global $has_featured_glob;
    if(!isset($has_featured_glob) || $has_featured_glob === 'right'):
      $has_featured_glob = 'left';
    elseif( $has_featured_glob === 'left' ):
      $has_featured_glob = 'right';
    endif;
  }
}

if($length === 4 || $length === 8 || $no_featured) {
  $has_featured = false;
} else {
  global $has_featured_glob;
  switch_featured();
  $has_featured = $has_featured_glob;
}

// Next
if($length > 8){
  $length = 7;
  $current = array_slice($posts, 0, $length - 1);
  $next = array_slice($posts, $length - 1);
  $posts = $current;
}

// Injection
if($injection){
  $offset = $grid_index * 7;
  if(!isset($injection['position']) || $injection['position'] == '') $injection['position'] = $length;
  $position = $injection['position'] - $offset;
  if($position <= $length){
    if(!$no_featured){
      if(!isset($injection_positions_by_length[$length])){
        $allowed = $injection_positions_by_length[8]['allowed'];
        $default = $injection_positions_by_length[8]['default'];
      } else {
        $allowed = $injection_positions_by_length[$length]['allowed'];
        $default = $injection_positions_by_length[$length]['default'];
      }
      if(!in_array($position, $allowed)) $position = $default;
      if($position) $position -= 1;
    }
    $start = array_slice($posts, 0, $position);
    $end = array_slice($posts, $position);
    $posts = array_merge($start, array($injection), $end);
    $injections = array_slice($injections, 1);
  }
}

// Layout design
$i = 0;
if($length === 2) $module = 2;
else if ($length > 4) $module = ($length - 3) % 4;
else $module = 0;

// Grid
foreach($posts as $key => $post):

  if(!is_object($post)){
    $post['order'] = $i;
  } else {
    $post->i = $i;
    $post->is_featured = !$i && $has_featured;
    $post->is_featured_desktop = false;
    $post->order = $i;
    if(!$i && !$grid_index) $post->is_first = true;
    else $post->is_first = false;

    if(!$no_featured):
      if($grid_index && $length === 6 && $has_featured === 'right'){
        if($i === 2 || $i == 3) {
          $post->is_featured = true;
        } else {
          $post->is_featured = false;
        }
      } else {
        if(
          $length == 2 ||
          ( $module === 2 && ( $i === $length - 2 || $i === $length - 1 ) ) ||
          ( $module === 3 && ( $i === $length - 1 || $i == 0 ) )
        )
          {
          $post->is_featured_desktop = true;
          $post->is_featured = false;
        }
        if($post->is_featured && $length > 2 && $has_featured === 'right') $post->order = 3;
      }
    endif;
  }

  $i++;

  $posts[$key] = $post;

endforeach;

if(!$not_first_iteration): ?>
<div class="<?= $wrapper_class ?> posts-smart-grid">
<?php endif;
?>

    <!-- Posts Grid -->
    <div class="flex flex-wrap -mx-gutter1/2 items-stretch">
        <?php foreach($posts as $post):
          $data = (array) $post;
          $data['post'] = $post;
          $module = $data['module'] ?? 'main-2020/post-card';
          ?>
          <?php if(!is_object($post) && !isset($post['oembed'])): ?>
              <div class="px-gutter1/2 ml:w-1/4 sm:w-1/2 w-full ml:order-<?=$post['order']?> flex items-stretch mb-e45">
                <?php brrl_the_module($post['module'], $post); ?>
              </div>
          <?php elseif(!is_object($post) && isset($post['oembed'])): ?>
            <div class="flex items-stretch ml:flex -mx-gutter1/2 xs:mx-0 xs:px-gutter1/2 ml:w-1/2 w-screen xs:w-full mb-e45 ml:order-<?=$post['order']?>">
              <?php brrl_the_module('main-2020/video', $post); ?>
            </div>
          <?php else:
            if($is_mini) {
              $data['is_featured'] = false;
              $data['is_mini'] = true;
              $desktop_featured = false;
              $mobile_featured = false;
            } else {
              $desktop_featured = $data['is_featured'] || $data['is_featured_desktop'];
              $mobile_featured = $data['is_featured'] && $data['is_first'];
            }
            $data['social_share'] = $social_share;
            
            //Only ipad + desktop featured card
            if( $desktop_featured ): $data['is_featured'] = true; ?>
              <div class="_hidden ml:block -mx-gutter1/2 xs:mx-0 xs:px-gutter1/2 ml:w-1/2 w-screen xs:w-full mb-e45 ml:order-<?=$post->order?>">
                <?php brrl_the_module($module, $data); ?>
              </div>
            <?php 
            endif;

            //Mobile cards
            $data['class'] = $post_card_class;
            if(!$mobile_featured) $data['is_featured'] = false
            ?>
            <div class="<?= $desktop_featured ? "block ml:hidden":''?> <?= $mobile_featured ? "-mx-gutter1/2 xs:mx-0 xs:px-gutter1/2 ml:w-1/2 w-screen xs:w-full":"px-gutter1/2 w-full ". $col_class ?> ml:order-<?=$post->order?> mb-e45">
              <?php brrl_the_module($module, $data); ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
    </div>

<?php if($next):

$grid_index++;

brrl_the_module('main-2020/posts-smart-grid',array(
  'grid_index' => $grid_index,
  'injections' => $injections,
  'not_first_iteration' => true,
  'posts' => $next,
  'social_share' => $social_share
));

else:

  if($length === 6 && !$no_featured) switch_featured();

endif;
?>
<?php if(!$not_first_iteration): ?>
</div>
<?php endif; ?>