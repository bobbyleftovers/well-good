<?php

namespace WG\Services;

class Comscore {

  static function print_script() {
    ?>
    <!-- comScore Tag -->
    <script>
          var _comscore = _comscore || [];
          _comscore.push({ c1: "2", c2: "19765212" });
          (function() {
              var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
              s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
              el.parentNode.insertBefore(s, el);
          })();
    </script>
    <noscript>
      <img src="http://b.scorecardresearch.com/p?c1=2&c2=19765212&cv=2.0&cj=1" />
    </noscript>
    <?php
  }

}