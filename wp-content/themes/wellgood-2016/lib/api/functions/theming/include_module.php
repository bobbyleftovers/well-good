<?php

function include_module($name, $module){
  include(get_template_directory() . get_module_path($name));
}