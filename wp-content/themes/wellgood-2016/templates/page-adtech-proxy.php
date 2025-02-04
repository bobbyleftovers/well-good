<?php
/* Template Name: AdTech IFrame Proxy */
?>
<!DOCTYPE html>
<!--
  Copyright 2010-2015 AOL Platforms.

  Iframe proxy file to be hosted on publisher servers.
-->
<html>
  <head></head>
  <body leftmargin="0" topmargin="0">
    <script>
      var allowed_domains = ['adserver.adtech.de', 'adserver.adtechus.com',
                             'aka-cdn.adtech.de', 'aka-cdn-ns.adtech.de', 'aka-cdn-ns.adtechus.com',
                             'pictela.net', 'ads.pictela.net', 'secure-ads.pictela.net'];
      try {
        var adtechIframeHashArray = self.document.location.hash.substring(1).split('|ifv|');
        var domain = adtechIframeHashArray[0].match(/^https?:\/\/([^:^\/]*)/i)[1];
        for (var d in allowed_domains) {
          if (allowed_domains[d] == domain) {
            document.write('<scr' + 'ipt type="text/javascript" src="' + adtechIframeHashArray[0] + '"></scr' + 'ipt>');
            break;
          }
        }
      } catch (e) {}
    </script>
  </body>
</html>
