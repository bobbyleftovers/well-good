<?php

global $module_once;

$module_once = array();

/**
 * Pass arguments into a module and get returned HTML
 *
 * @param $module_name Name of module
 * @param array $args Key-value pairs which will be extracted as variables in module templates
 * @return string
 */
function brrl_get_module( $module_name, $args = array() ) {
  ob_start();
  brrl_the_module( $module_name, $args );
  return ob_get_clean();
}

function brrl_get_module_once($module_name, $args){

  global $module_once;

  if(isset($module_once[$module_name])) return $module_once[$module_name];

  $module_once[$module_name] = get_module($module_name, $args);

  return $module_once[$module_name];
}


/**
 * Pass arguments into a module and render its HTML output
 * @param $module_name Name of module
 * @param array $args Key-value pairs which will be extracted as variables in module templates
 * @return bool|string
 */
function brrl_the_module( $module_name, $args = array() ) {
  if ( empty( $module_name ) ) {
    return;
  }

  if(is_array($args) || is_object($args))  {
    $args_array = (array) $args;
    extract( $args_array, EXTR_SKIP );
  }

  include( TEMPLATEPATH . get_module_path($module_name));
}

function brrl_the_module_once($module_name, $args){

  global $module_once;

  if(isset($module_once[$module_name])) return;

  echo brrl_get_module_once($module_name, $args);
}

global $is_footer_vue_components;
$is_footer_vue_components = false;

function brrl_register_vue_component($module_name, $args = array()){

  $args['vue'] = $args['vue'] ?? true;
  $args['is_vue'] = $args['is_vue'] ?? true;
  $args['is_component'] = $args['is_component'] ?? true;

  global $is_footer_vue_components;

  if($is_footer_vue_components){
    brrl_the_module_once($module_name, $args);
  } else {
    add_action('vue_components', function() use ($module_name, $args){
      brrl_the_module_once($module_name, $args);
    });
  }
}

function brrl_footer_register_vue_components(){
  global $is_footer_vue_components;
  $is_footer_vue_components = true;
  ?>
  <div v-cloak class="brrl-vue-components">
    <?php do_action('vue_components'); ?>
  </div>
<?php $is_footer_vue_components = false;
}

add_action('wp_footer', 'brrl_footer_register_vue_components', 999);
