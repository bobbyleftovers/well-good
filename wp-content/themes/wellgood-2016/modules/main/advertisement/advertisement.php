<?php
$args = isset($post->advertisement_field) ? $post->advertisement_field : array();
$slots = array_key_exists('slots', $args) ? $args['slots'] : '';
$page = array_key_exists('page', $args) ? $args['page'] : 0;
$iteration = array_key_exists('iteration', $args) ? $args['iteration'] : 0;
$sticky = array_key_exists('sticky', $args) ? $args['sticky'] : '';
$class = array_key_exists('class', $args) ? $args['class'] : array();
$properties = array();
$data_slots = array();

if ($slots) :
  if (is_string($slots)) :
    array_unshift($class, "container__ad--$slots");
    array_push($data_slots, $slots);
  else :
    foreach($slots as $slot) :
      array_unshift($class, "container__ad--$slot");
      array_push($data_slots, $slot);
    endforeach;
  endif;
endif;
array_unshift($class, 'container', 'container__ad');

if (isset($page)) :
  array_push($properties, 'data-ad-page="' . $page . '"');
endif;
if (isset($iteration)) :
  array_push($properties, 'data-ad-iteration="' . $iteration . '"');
endif;
if ($sticky) :
  array_push($properties, 'data-ad-sticky="true"');
endif;
if ($data_slots) :
  array_push($properties, 'data-ad-slots=\'' . json_encode($data_slots) . '\'');
endif;
?>

<div class="<?= implode(' ', $class); ?>" <?= implode(' ', $properties); ?>></div>
