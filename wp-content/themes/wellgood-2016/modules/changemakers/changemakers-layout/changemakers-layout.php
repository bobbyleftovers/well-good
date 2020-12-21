<?php

// Assets bundles
set_theme_template('main-2020');
set_theme_template('gutenberg');
set_theme_template('changemakers');

// Header
get_header();

// Layout
?>
<main class=" theme-main-2020 changemakers-layout">
  <?php $render(); ?>
</main>

<?php
// Footer
get_footer();

?>