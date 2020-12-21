<h3 class="text-h2-styleguide">Page templating</h3>
<?php brrl_the_module('styleguide-2020/code', array(
'lang' => 'php',
'code' => "// Main bundles
set_theme_template('main-2020');         // main-2020.[hash].js + main-2020.[hash].css
set_theme_template('specific-bundle');   // specific-bundle.[hash].js + specific-bundle.[hash].css

// Specific scripts
add_theme_js('specific-script-1');       // specific-script-1.[hash].js
add_theme_css('specific-script-2');      // specific-script-2.[hash].css

// Critical CSS
set_theme_critical_css('main-2020');     // main-2020-critical.min.css

// Header
get_header();

  // Page content

// Footer
get_footer();
"
));
?>

<div class="font-sans font-sm mb-e85">
  This example will also add the classes <span class="inline-code">theme-main-2020</span> and <span class="inline-code">theme-specific-bundle</span> to the body tag.
</div>
