<?php

  /**
   * Loop through an array of acf field names to check whether or not an image exists in that location
   * @param array $images An array of acf field names to check
   * @param array $size Desired image size
   * @return string|bool $image The image url, if found | Returns false if no image matched
   */

   
function acf_check_fallback_images($images, $size = 'large') {
   foreach( $images as $image ){
    if( get_field($image) ){
      $image = get_field($image);
      return $image['sizes'][$size];
    }else {
      return false;
    }
   }

}