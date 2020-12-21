<?php if (article_is_branded(get_the_ID()) || get_field('disable_narrativ')): ?>
  <!-- Narrative Conditional / Disable if Branded -->
  <script>
    window.NRTV_EVENT_DATA = { donotlink: true };
  </script>
<?php endif; ?>

<?php if (get_field('disable_skimlinks')): ?>
  <script type="text/javascript">
    var skimlinks_settings = { noskim: true };
  </script>
<?php endif; ?>

<?php if (!is_production()): ?>
<!-- Injecting this just for DEV/Staging -->
<script type="text/javascript">
  (function(window, document, account) {
    window.skimlinks_exclude = ["shop-links.co", "shop-edits.co"];
    var b = document.createElement("script");
    b.type = "text/javascript";
    b.src = "https://static.narrativ.com/tags/" + account + ".js";
    b.async = true;
    var a = document.getElementsByTagName("script")[0];
    a.parentNode.insertBefore(b,a);
  })(window, document, "wellandgood");
</script>
<?php endif; ?>
