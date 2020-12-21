<?php

/**
*
*  get formatted $_SERVER env vars
*
*/

if(!function_exists('get_server_var')){
    function get_server_var($var) {
        if(!isset($_SERVER[$var])) return null;
        if($_SERVER[$var] === 'true') return true;
        if($_SERVER[$var] === 'false') return false;
        if($_SERVER[$var] === '1') return 1;
        if($_SERVER[$var] === '0') return 0;
        return $_SERVER[$var];
    }
}