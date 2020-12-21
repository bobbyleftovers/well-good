<?php

/**
*
*  Is production test
*
*/

if(!function_exists('is_production')){
    function is_production(){
      //leaf
      if(get_server_var('STG_HOST')) return get_server_var('IS_PRODUCTION');
      //pantheon
      return !isset($_SERVER['LANDO']);
    }
}