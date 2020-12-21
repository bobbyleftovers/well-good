<?php

/**
 * Get the featured image fallback from theme options
 * @return object The Image object of the featured image
 */
global $featured_image_fallback;

function wag_get_fallback_image($param = null) {
  global $featured_image_fallback;
  if(!$featured_image_fallback) $featured_image_fallback = get_field('featured_image_fallback', 'option');
  if($param) return $featured_image_fallback[$param];
  return $featured_image_fallback;
}