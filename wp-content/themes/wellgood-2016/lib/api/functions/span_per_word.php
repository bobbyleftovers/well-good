<?php

/**
 * Wrap each word of a string in a span
 * @param string $input - string to wrap
 * @return object $result - string with spans 
 * around each word
 */
function span_per_word($input){
  return preg_replace('([-!$%^&*;()a-zA-Z.,!?0-9]+(?![^<]>))', '<span class="word">$0</span>', $input);
}