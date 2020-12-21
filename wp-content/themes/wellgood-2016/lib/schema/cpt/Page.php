<?php

namespace WG\Schema\Cpt;

use WG\Schema\Cpt\Custom_Post_Type;

class Page extends Custom_Post_Type { 

  function __construct(){
    $this->add_exerpt();
    $this->register_taxonomy('post_tag');
    add_filter( 'page_attributes_dropdown_pages_args', array($this,'attributes_dropdown_pages_args'), 1, 1 );
    add_filter( 'quick_edit_dropdown_pages_args', array($this,'attributes_dropdown_pages_args'), 1, 1 );
    add_filter('init', array($this,'add_excerpt_support'), 99);
  }

  function add_excerpt_support(){
    add_post_type_support( 'page', 'excerpt' );
  }

  /**
  * Inlcude draft pages in parent page dropdowns
  * @link https://wordpress.stackexchange.com/questions/3346/how-can-i-set-a-draft-page-as-parent-without-publishing
  * @link https://www.mightyminnow.com/2014/09/include-privatedraft-pages-in-parent-dropdowns/
  */

  function attributes_dropdown_pages_args($dropdown_args) {
    $dropdown_args['post_status'] = array('publish','draft');

    return $dropdown_args;
  }

}