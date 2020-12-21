<?php
// Header
get_header();

// Layout
?>
<main class="relative theme-main-2020 renew-year-2021-layout<?= $is_parent ? ' renew-year-2021-layout--home': ' renew-year-2021-layout--child'?>">
  <?php 
    foreach($modules as $module):
      brrl_the_module("renew-year-2021/renew-year-2021-$module", $args);
    endforeach;
    ?>
</main>

<?php
// CSS utilities
brrl_the_module("renew-year-2021/renew-year-2021-dynamic-css", $args);

// Footer
get_footer();

?>