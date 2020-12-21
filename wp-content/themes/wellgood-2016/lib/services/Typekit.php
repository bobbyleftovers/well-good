<?php

namespace WG\Services;

class Typekit {

  function __construct(){
    add_action( 'wp_head', array( $this, 'wp_print_head_scripts' ), 5 );
  }

  function wp_print_head_scripts() { 
    ?>

    <script type="text/javascript">
      // Typekit
      (function() {
        var tk = document.createElement('script');
        var d = false;
        tk.src = '//use.typekit.net/tjj3rxp.js';
        tk.type = 'text/javascript';
        tk.async = true;
        tk.onload = tk.onreadystatechange = function() {
          var rs = this.readyState;
          if (d || rs && rs != 'complete' && rs != 'loaded') return;
          d = true;
          try { Typekit.load({async: true}); } catch (e) {}
        };
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(tk, s);
      })();
    </script>
    <?php 
  }
}