<?php

/**
*
*  Is webpack dev
*
*/

if(!function_exists('is_dev')){
    function is_dev(){
      return  (defined('NODE_ENV') && NODE_ENV === 'development' && is_dev_proxy());
    }
}
