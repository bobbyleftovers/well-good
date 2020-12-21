<?php

/**
 * Sort an array by date
 * @link https://stackoverflow.com/questions/2910611/php-sort-a-multidimensional-array-by-element-containing-date/40463067
 */
function wg_post_date_comparison($a, $b) {
  $a_date = strtotime($a->post_date);
  $b_date = strtotime($b->post_date);
  return $b_date - $a_date;
}