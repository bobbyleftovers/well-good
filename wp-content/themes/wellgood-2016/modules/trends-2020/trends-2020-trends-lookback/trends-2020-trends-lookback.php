<?php

function tl_add_class(&$original_class, $added_class){
  return $original_class .= " $added_class ";
}

function tl_get_pattern($env = array()){

  //hydrate default env
  $env = array_merge(array(
    'key' => 0,
    'total_length' => 0,
    'last_double_side_l' => 'right',
    'last_double_side_m' => 'right',
    'counter_l' => 0,
    'counter_m' => 0,
    'counter_s' => 0
  ), $env);

  //patterns
  $pattern_l = array( 2, 1, 1, 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1 );
  $pattern_m = array( 2, 1, 1, 1, 1, 1, 2, 1, 1, 1 );
  $pattern_s = array( 2, 1, 1, 1, 1);

  //real index
  $index = $env['key'] + 1;
  $total_to_end = $env['total_length'] - $env['key'];

  //get pattern values
  $col_l = $pattern_l[$env['counter_l']];
  $col_m = $pattern_m[$env['counter_m']];
  $col_s = $pattern_m[$env['counter_s']];

  //remainders and grouping
  $group_size_l = 7;
  $group_size_m = 5;
  $group_size_s = 5;

  $remainder_l = $env['total_length'] % $group_size_l;
  $remainder_m = $env['total_length'] % $group_size_m;
  $remainder_s = $env['total_length'] % $group_size_s;

  switch($remainder_l){
    case 6:
      $remainder_threshold_l = 3;
    break;
    case 5:
      $remainder_threshold_l = 2;
    break;
    default:
      $remainder_threshold_l = $remainder_l <= 4 ? $remainder_l : 4;
    break;
  }

  switch($remainder_m){
    case 4:
      $remainder_threshold_m = 2;
    break;
    default:
      $remainder_threshold_m = $remainder_m <= 3 ? $remainder_m : 3;
    break;
  }

  switch($remainder_s){
    case 4:
      $remainder_threshold_s = 1;
    break;
    case 3:
      $remainder_threshold_s = 0;
    break;
    break;
    default:
      $remainder_threshold_s = $remainder_s <= 3 ? $remainder_s : 3;
    break;
  }

  //breakpoints
  $breakpoint_m = 'l';
  $breakpoint_s = 'm';
  $breakpoint_xs = 's';

  //run the logic
  $class_name = '';
  $env['in_grid_l'] = true;
  $env['in_grid_m'] = true;
  $env['in_grid_s'] = true;

  //logic for L
  if($total_to_end  <= $remainder_l && $total_to_end <= $remainder_threshold_l){
    $env['in_grid_l'] = false;
    switch($remainder_threshold_l){
      case 4:
        tl_add_class($class_name, 'trends-2020__col-25');
      break;
      case 3:
        if(($total_to_end === 3 && $env['last_double_side_l'] === 'right') || ($total_to_end === 1 && $env['last_double_side_l'] !== 'right')) {
          tl_add_class($class_name, 'trends-2020__col-50');
        } else {
          tl_add_class($class_name, 'trends-2020__col-25');
        }
      break;
      case 2:
        /*if($remainder_l < $env['total_length'] && ($remainder_l > $remainder_threshold_l && $env['last_double_side_l'] !== 'right')){
          tl_add_class($class_name, 'trends-2020__col-25');
        } else {
          tl_add_class($class_name, 'trends-2020__col-50');
        }*/
        tl_add_class($class_name, 'trends-2020__col-50');
      break;
      case 1:
        tl_add_class($class_name, 'trends-2020__col-50');
      break;
    }
  } else {
    if($col_l == 2){
      $env['last_double_side_l'] = $env['last_double_side_l'] == 'left' ? 'right' : 'left';
      tl_add_class($class_name, 'trends-2020__col-50');
    } else {
      tl_add_class($class_name, 'trends-2020__col-25');
    }
  }

  //logic for M
  if($total_to_end  <= $remainder_m && $total_to_end <= $remainder_threshold_m){
    $env['in_grid_m'] = false;
    switch($remainder_threshold_m){
      case 3:
        tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-33');
      break;
      case 2:
        if(($total_to_end === 2 && $env['last_double_side_m'] === 'right') || ($total_to_end === 1 && $env['last_double_side_m'] !== 'right')) {
          tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-66');
        } else {
          tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-33');
        }
      break;
      case 1:
        tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-66');
      break;
    }
  } else {
    if($col_m == 2){
      $env['last_double_side_m'] = $env['last_double_side_m'] == 'left' ? 'right' : 'left';
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-66');
    } else {
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_m.'-33');
    }
  }


  //logic for S
  if($total_to_end  <= $remainder_s && $total_to_end <= $remainder_threshold_s){
    $env['in_grid_s'] = false;
    if($remainder_threshold_s == 2){
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_s.'-50');
    } else {
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_s.'-100');
    }
  } else {
    if($col_s == 2){
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_s.'-100');
    } else {
      tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_s.'-50');
    }
  }

  //logic for XS
  tl_add_class($class_name, 'trends-2020__col-'.$breakpoint_xs.'-100');

  //advance pointer
  $env['counter_l']++;
  $env['counter_m']++;
  $env['counter_s']++;

  if(!isset($pattern_l[$env['counter_l']])) $env['counter_l'] = 0;
  if(!isset($pattern_m[$env['counter_m']])) $env['counter_m'] = 0;
  if(!isset($pattern_s[$env['counter_s']])) $env['counter_s'] = 0;

  //retrurn class & env
  $env['class'] = $class_name;
  return $env;

}

?>

<div class="trends-2020-trends-lookback trends-2020__row">

  <?php

  if($module['posts']):

    $pattern = array(
      'total_length' => sizeof($module['posts'])
    );

    foreach ($module['posts'] as $key => $article):

      $article['is_lookback'] = true;

      $pattern['key'] = $key;

      $pattern = tl_get_pattern($pattern);

      ?> <div class="trends-2020-trends-lookback__article-col <?=$pattern['class']?>"> <?php
            include_module('trends-2020/trends-2020-article', $article);
      ?> </div> <?php
    endforeach;
  endif;

  ?>

</div>