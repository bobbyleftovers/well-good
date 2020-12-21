<?php

/**
*
*  Is localhost test
*
*/

if(!function_exists('is_local')){
  function is_local(){
    return (get_server_var('STG_HOST') === null && isset($_SERVER['LANDO']) && $_SERVER['LANDO'] == 'ON' && $_SERVER['PANTHEON_ENVIRONMENT'] == 'dev');
  }
}


