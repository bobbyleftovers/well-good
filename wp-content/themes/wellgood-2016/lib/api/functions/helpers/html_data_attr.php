<?php

/**
 * Return data attribute markup if query is not for a feed
 */
function html_data_attr($attr, $value) {
  return ( !is_feed() ) ? "$attr=\"$value\"" : '';
}