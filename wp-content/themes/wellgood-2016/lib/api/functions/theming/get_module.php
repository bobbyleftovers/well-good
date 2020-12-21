<?php

function get_module($module_name = '', $field_name = '', $sub_field_name = '', $sub_sub_field_name = '', $sub_sub_sub_field_name = '') {
  if(empty($module_name)) {
    return false;
  }

  ob_start();

  the_module($module_name, $field_name, $sub_field_name, $sub_sub_field_name, $sub_sub_sub_field_name);

  $html = ob_get_contents();

  ob_end_clean();

  return $html;
}