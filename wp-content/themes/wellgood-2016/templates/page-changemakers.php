<?php
/* Template Name: Changemakers */

// Current post
global $post;

// Data passed to all components
$data = array(
  'post' => $post
);

// Render internal components
$data['render'] = function() use ($data) {
  brrl_the_module('changemakers/changemakers-hero', $data);
  brrl_the_module('changemakers/changemakers-content', $data);
};
 
//Render layout
brrl_the_module('changemakers/changemakers-layout', $data);
?>