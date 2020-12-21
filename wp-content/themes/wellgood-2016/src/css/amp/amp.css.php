<?php

// Include functions and vars
$src = get_template_directory().'/src/css/amp';

function includeAmpModule($module, $part = null){
  if(!$part) $part = $module.'-amp';
  include get_template_directory().get_module_path($module, $part.'.css');
}

// only CSS here please...
$logo                 = get_field('amp_header_logo', 'options');
$header_bg_color      = get_field('header_background_color', 'options');
$header_border_color  = get_field('header_border_color', 'options');
$link_color           = get_field('link_color','options');
$logo_position        = get_field('logo_position','options');

$logo_graphic_src = get_template_directory_uri() . '/assets/img/w-g-logo-black.svg';

// Dynamic css
?>
header.amp-wp-header {
  background-color: <?= $header_bg_color; ?>;
  border-bottom: 1px solid <?= $header_border_color; ?>;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 45px;
}

header.amp-wp-header a {
  background-image: url('<?= $logo_graphic_src; ?>');
  background-position: <?=$logo_position;?>;
  height: 10px !important;
  width: 162px;
  margin: 0 auto;
  background-size: 162px 10px !important;
}
.amp-wp-article a {
color: <?=$link_color;?>
}

.amp-wp-article-content blockquote:not(.tiktok-embed) {
  background-color: transparent;
  border-left: 2px solid #42676B;
  color: #707070;
  padding: 0 20px;
}

.amp-wp-article-content blockquote.tiktok-embed {
  background-color: transparent;
  padding: 0;
  margin-left: 0;
  margin-right: 0;
}

.amp-wp-article-content blockquote.tiktok-embed amp-iframe {
  margin-left: 0;
  margin-right: 0;
}

<?php
  // Basic amp layout
  include $src.'/amp.css';

  // Modules
  includeAmpModule('shop-links');
  includeAmpModule('recipe-card');
  includeAmpModule('slideshow');
  includeAmpModule('franchise-blurb');
  includeAmpModule('related-content');
  includeAmpModule('wg-inline-shopable');
  includeAmpModule('wg-listacle', 'wg-listacle-amp');
  includeAmpModule('main-2020/featured-content-promo', 'featured-content-promo-amp');
  //includeAmpModule('slideshow', 'collapsible-captions-amp');
?>
