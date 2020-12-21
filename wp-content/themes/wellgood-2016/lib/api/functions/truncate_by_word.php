<?php

/**
 * Truncate strings by word
 * 
 * @param string $string - String to be truncated
 * @param integer $length - Max word count of truncated string
 * @param string $after - Text after truncated string
 * @return string $trunc_string - Truncated text
 */
function truncate_by_word($string, $length, $after = '') {
  $string_words = explode(' ', $string, $length);
  if (count($string_words) >= $length) :
      array_pop($string_words);
      $trunc_string = implode(' ', $string_words) . $after;
  else :
      $trunc_string = implode(' ', $string_words);
  endif;
  return $trunc_string;
}
