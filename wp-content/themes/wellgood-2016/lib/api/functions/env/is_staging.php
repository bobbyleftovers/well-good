<?php

/**
*
*  Is staging test
*
*/

if(!function_exists('is_staging')){
    function is_staging(){
      return get_server_var('STG_HOST') && !get_server_var('IS_PRODUCTION');
    }
}