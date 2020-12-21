<?php

/**
 * Build a gradient string from ACF field values.
 * @param int ID of post from which to retrieve gradient fields
 * @return string background-image: repeating-linear-gradient(199deg, $color1, $gradient_color_1 2000px, $color2 4000px);
 */
function get_gradient($id) {
  $gradient_color_1 = get_field('gradient_color_1', $id) ?: '#EFCA71';
  $gradient_color_2 = get_field('gradient_color_2', $id) ?: '#F18255';
  $gradient = "background-image: repeating-linear-gradient(199deg, $gradient_color_2, $gradient_color_1 2000px, $gradient_color_2 4000px);";
  return $gradient;
}