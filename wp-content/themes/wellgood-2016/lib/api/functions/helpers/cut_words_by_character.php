<?php

function cut_words_by_character($words = '', $length = 500) {
  $string = strip_tags($words);
  if (strlen($string) > $length) {
      $stringCut = substr($string, 0, $length);
      $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
  }
  return $string;
}