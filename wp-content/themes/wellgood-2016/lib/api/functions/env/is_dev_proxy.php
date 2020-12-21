<?php

/**
*
*  Is webpack dev
*
*/

if(!function_exists('is_dev_proxy')){
    function is_dev_proxy(){
      return isset( $_SERVER['HTTP_X_DEV'] );
    }
}
