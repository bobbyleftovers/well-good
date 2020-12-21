<?php

/**
*
*  Get host name
*
*/

if(!function_exists('get_host')){
    function get_host(){
      return get_server_var('STG_HOST') ?? $_SERVER['HTTP_HOST'] ?? $_ENV['HTTP_HOST'];
    }
}