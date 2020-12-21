<?php
/* Template Name: Ambassadors Newsletter Landing */

get_header();?>

<article class="page">

  <!-- Heading --->
	<?php the_module('ambassador-newsletter-page'); ?>

  <!-- Trending articles --->
  <?php the_module('trending-articles'); ?>

  <!-- WG Franchises --->
  <?php the_module('franchises-slideshow'); ?>

  <!-- Instagram Feed --->
	<div class="container">
		<?php if ( get_field( 'show_instagram_feed' ) == 'yes' ): ?>
      <?php the_module('instagram-feed'); ?>
		<?php endif; ?>
	</div>
</article>

<?php
get_footer();
?>
