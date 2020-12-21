<?php
global $post;

$is_transparent = $is_transparent ?? false;
$not_sticky = $not_sticky ?? false;
$text_color = $text_color ?? 'black';

$featured_article_link = get_field('featured_navigation_link', 'options');
$featured_article_label = get_field('featured_navigation_label', 'options');
$featured_article_color = get_field('featured_navigation_color', 'options');
$featured_article_image = get_field('featured_navigation_image', 'options');
$featured_article_heading = get_field('featured_navigation_title', 'options');
$has_featured_article_image = isset($featured_article_image['ID']) && is_numeric($featured_article_image['ID']);
$header_tag = is_front_page()  ? 'h1' : 'div';
// $logo_graphic = get_field('logo_graphic', 'options');
// $logo_graphic_src = $logo_graphic ? $logo_graphic['sizes']['thumbnail'] : '';
if($text_color == 'white'){
  $logo_graphic_src = get_template_directory_uri().'/assets/img/w-g-logo-white.svg';
} else {
  $logo_graphic_src = get_template_directory_uri().'/assets/img/w-g-logo-black.svg';
}
$featured_article_class = 'featured-article';
$is_home = is_page_template( 'templates/page-home.php' );
$is_trends_2020 = is_page_template('templates/page-2020-hub.php');
if($has_featured_article_image) {
	$featured_article_class .= ' featured-article--image';
	$featured_article_style = 'background-image: url('. $featured_article_image['url'] .')' ;
} else {
	$featured_article_style = 'background-color:' . $featured_article_color;
}

$header_top_classes = array('header-top');
if ( $is_home ) :
	// array_push($header_top_classes, 'header-top--home');
endif;

$header_classes = array('header');
if ( $is_home ) :
	// array_push($header_classes, 'header--home');
endif;

if (!$is_trends_2020) :
	the_module('advertisement', array(
		'class' => array(
      'show-mobile'
		),
		'slots' => array(
			'horizontal',
			'adhesion'
		),
		'page' => 0,
		'iteration' => 0
	));
else :
	the_module('advertisement', array(
		'class' => array(
      'show-mobile'
		),
		'slots' => array(
			'adhesion'
		),
		'page' => 0,
		'iteration' => 0
	));
endif; 


// Transpaarent
if ( $is_transparent ) :
  array_push($header_classes, 'is-transparent');
  $bg_class = 'bg-transparent';
else:
  $bg_class = 'bg-white';
endif;

// Text color
if($text_color === 'black') $text_color = 'gray-dark';
array_push($header_classes, "text-$text_color");
if($text_color === 'white') $link_class = 'text-white';
else $link_class = '';
// Don't purge
// text-gray-dark text-white

//Stickyness
if($not_sticky) array_push($header_classes, "not-sticky");

?>

<div class="search-overlay bg-black opacity-0 inset-0 fixed js-search-close"></div>
<div class="menu-drawer-container fixed inset-0 h-full w-full theme-main-2020">
  <span class="drawer-overlay js-drawer-close"></span>
	<div class="menu-drawer absolute top-0 left-0 w-1/1 h-screen p-e20 bg-white lg:p-e40">
		<div class="menu-drawer__menu">
			<?php wp_nav_menu( array(
				'menu' => 'Menu Drawer',
				'sort_column' => 'menu_order',
				'theme_location' => 'drawer',
				'link_before' => '<span class="label">',
				'link_after' => '</span>',
				'menu_class' => 'nav__menu nav__primary',
				'container' => false
			) ); ?>
		</div>
    <?php if ($featured_article_label && $featured_article_link) : ?>
			<hr class="hr--grey" />
			<div class="drawer-featured">
				<h3 class="drawer-featured__heading" style="background-color: <?= $featured_article_color; ?>"><?= $featured_article_heading; ?></h3>
				<a href="<?= $featured_article_link; ?>" class="drawer-featured__link" data-vars-event="hamburger spotlight"><?= $featured_article_label; ?></a>
			</div>
    <?php endif; ?>
		<hr class="hr--grey" />
		<div class="drawer-cta-menu">
				<?php wp_nav_menu( array(
					'menu' => 'Drawer Sub-Menu',
					'sort_column' => 'menu_order',
					'link_before' => '<span class="label">',
					'link_after' => '</span>',
					'menu_class' => 'nav__menu nav__primary drawer-submenu',
					'container' => false
				) ); ?>
    </div>
    <?php
      $spotlight_enabled = get_field('spotlight_bar_enabled', 'options');
      if ($spotlight_enabled === true):
    ?>
      <?php brrl_the_module('spotlight-bubble'); ?>
    <?php endif;?>
		<hr class="hr--grey" />
		<div class="drawer-signup">
			<div class="drawer-signup__heading mt-e15 text-seafoam-dark text-h5">Become an Insider</div>
			<?php the_module('signup-form', array(
        'form_id' => 'sidebar',
        'location' => 'hamburger'
			)); ?>
		</div>
		<div class="drawer-social">
			<?php the_module('social-media-links', array(
        'menu_class' => 'flex justify-start items-center'
      )); ?>
		</div>
		<button href="#" class="drawer-close js-drawer-close">
      <span class="visually-hidden">Toggle Drawer</span>
    </button>
	</div>
</div>

<?php if($not_sticky): ?><div class="relative w-1/1"><?php endif;?>

<header class="<?= implode($header_classes, ' ') ?>" data-module-init="header">
  <?php /*
  <div class="header-top <?=$bg_class?> theme-main-2020">
    <div class="container flex items-end h-e45 relative">
      <button class="header-top__cta ml-auto text-seafoam-dark font-serif inline-flex items-center" data-signup-form-toggle>
        <span>Sign up for emails</span>
        <span class="arrw down ml-e5 mr-e8 -mt-e2"></span>
      </button>
      <div class="header-top__signup bg-tan-light p-e20 absolute" data-signup-form>
        <div class="h3 text-left text-seafoam-dark font-serif">Become an Insider</div>
        <?php the_module('signup-form', array(
          'form_id' => 'sidebar',
          'newsletter_button_text' => 'Sign up'
        )); ?>
      </div>
    </div>
  </div>
  */?>
	<div class="header__inner <?=$bg_class?>">
    <div class="container">
      <div class="header__inner__container flex items-center justify-between">
        <a href="#menu" class="nav-trigger" aria-haspopup="true" aria-label="Open Menu">
          <span class="nav-trigger__bar nav-trigger__bar--top"></span>
          <span class="nav-trigger__bar nav-trigger__bar--middle"></span>
          <span class="nav-trigger__bar nav-trigger__bar--bottom"></span>
          <span class="nav-trigger__text" style="opacity:0">Menu</span>
        </a>

        <a href="/" class="logo-wrapper w-full">
          <<?= $header_tag; ?> class="logo">
            <img class="block" src="<?= $logo_graphic_src; ?>" alt="<?= get_bloginfo('name');?>">
          </<?= $header_tag; ?>>
        </a>
        <div class="menu-header-menu-container">
          <?php
              wp_nav_menu( array(
                'sort_column' => 'menu_order',
                'theme_location' => 'header',
                'link_before' => '<span class="label '.$link_class.'">',
                'link_after' => '</span>',
                'menu_class' => 'nav__menu nav__primary',
                'container' => false
            ) );
          ?>
        </div>

        <button aria-expanded="false" class="header__search appearance-none inline-flex items-center justify-center" data-search-elem=".search-bar .search-bar__input">
          <span class="icon-search-thin-small"></span>
          <span class="icon-x"></span>
          <span class="visually-hidden">Toggle Search</span>
        </button>
      </div>
    </div>
	</div>
	<div class="search-bar bg-tan-light">
		<div class="search-bar__inner container">
      <form id="search-form" action="<?php echo home_url(); ?>">
        <label for="global-search" class="visually-hidden">Search</label>
        <input id="global-search" required class="search-bar__input" type="text" name="s" placeholder="Enter Search&hellip;" value="<?php /*echo get_search_query();*/ ?>">
				<button class="search-bar__submit">
          <span class="visually-hidden">Search Button</span>
          <span class="icon-search-thin-small"></span>
        </button>
			</form>
		</div>
  </div>
</header>
<?php if($not_sticky): ?></div><?php endif;?>

<?php
  if ($spotlight_enabled === true && is_front_page()):
?>
  <?php brrl_the_module('spotlight-bubble', array('classes' => 'spotlight-bubble--home')); ?>
<?php endif;?>
