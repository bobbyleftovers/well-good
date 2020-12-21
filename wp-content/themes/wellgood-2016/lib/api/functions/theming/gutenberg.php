<?php

use \WG\Settings\Gutenberg;

function brrl_the_content($post = false, $args = null){
  if(!$post) global $post;
  Gutenberg::the_content($post, $args);
}

function brrl_get_the_content($post = false, $args = null, $blocks = false){
  if(!$post) global $post;
  return Gutenberg::get_the_content($post, $args, $blocks);
}

function brrl_get_blocks($post = false, $args = null){
  if(!$post) global $post;
  return Gutenberg::get_blocks($post, $args);
}

