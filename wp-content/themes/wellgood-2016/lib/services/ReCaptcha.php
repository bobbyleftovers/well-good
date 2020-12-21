<?php

namespace WG\Services;

class ReCaptcha {

  function enqueue_recaptcha() {
    // Google reCaptcha
    wp_enqueue_script( 'recaptcha',
       'https://www.google.com/recaptcha/api.js',
       array(),
       false
     );
  }
  
}