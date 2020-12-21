<?php

namespace WG\Services;

class Instana {

  function __construct(){

    add_action('wp_head', array($this, 'inject_script'));

  }

  /**
   * Instana inline js
   *
   * @return void
   */
  function inject_script(){
    ?>
       <script>
        (function(c,e,f,k,g,h,b,a,d){c[g]||(c[g]=h,b=c[h]=function(){
        b.q.push(arguments)},b.q=[],b.l=1*new Date,a=e.createElement(f),a.async=1,
        a.src=k,a.setAttribute("crossorigin", "anonymous"),d=e.getElementsByTagName(f)[0],
        d.parentNode.insertBefore(a,d))})(window,document,"script",
        "https://eum.instana.io/eum.min.js","InstanaEumObject","ineum");
        ineum('reportingUrl', 'https://eum-red-saas.instana.io');
        ineum('key', "<?= is_production() ? 'ZPlrH8nqTBagwemuvm1--A' : 'CFS0O81lSWuGU2jA82CO0w' ?>");
      </script>
    <?php
  }
}
