<?php

function featured_image_has_caption() {
  $caption = get_featured_image_caption();
  return ! empty( $caption );
}