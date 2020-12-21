<?php

/**
* Add timestamp to tracking code from ACF field
* @param string $field post tracking code field
* @return string $image The image url, if found | Returns false if no image matched
*/

function prepare_timestamp_tracking_code($tracking_code) {

  $time = time();
  $tracking_code = str_replace('[timestamp]', $time, $tracking_code);
  return $tracking_code;

}