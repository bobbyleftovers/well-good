<?php

if ( ! function_exists( 'wg_resize' ) ) {

  function wg_resize_get_value_string($v){
    if($v === true){
      return 'true';
    } else if($v === false || $v === null ) {
      return 'false';
    } else {
      return $v;
    }
  }

  function wg_resize($img_url = false, $w = null, $h  = null , $c = false, $q = 70){
    if (!$img_url) $img_url = wag_get_fallback_image()['url'];
    if(!strpos($img_url, get_host())) return $img_url;
    $img_dir = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $img_url);
    $filename = basename($img_dir);
    $filename_without_extension = pathinfo($filename, PATHINFO_FILENAME);
    $extension = str_replace($filename_without_extension,"", $filename);

    $new_filename = $filename_without_extension.'_'.wg_resize_get_value_string($w).'x'.wg_resize_get_value_string($h).'_'.wg_resize_get_value_string($c).'_'.wg_resize_get_value_string($q).$extension;
    $new_dir = dirname($img_dir).'/'.$new_filename;
    $new_url = dirname($img_url).'/'.$new_filename;

    if(file_exists($new_dir)) return $new_url;

    $img = wp_get_image_editor( $img_dir );

    if ( ! is_wp_error( $img ) ) {
        $img->resize( $w, $h, $c );
        $img->set_quality( $q );
        $saved = $img->save( $new_dir );
        return $new_url;
    } else {
      return $img_url;
    }
  }
}
?>