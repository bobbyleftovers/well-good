<?php
	global $wp_query;
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$author_id = $author !== false ? $author->ID : 'false';
	$author_meta_id = 'user_'.$author_id;

	// Redirect if this author should be hidden
if ( ! get_field( 'show_author', $author_meta_id ) ) :
	header( 'HTTP/1.0 302 Found' );
	header( 'Location: http://www.wellandgood.com' );
endif;
?>
<?php get_header(); ?>

<main>
<?php
	$current_page   = max( 1, get_query_var( 'paged' ) );
	$author_profile = $current_page == 1 ? 1 : 0;
?>
	<div class="container">

	<?php if ( $author_profile ) : ?>
		<?php
		$story_author_avatar = get_avatar( $author_id, 480 );
		?>
		<section class="author-list-profile">
			<span class="avatar-wrapper author-list-profile__avatar"><?php echo $story_author_avatar; ?></span>
			<div class="author-list-profile__inner">
				<h1 class="big"><?php echo get_the_author(); ?></h1>
				<?php
				if ( ! empty( get_field( 'title', $author_meta_id ) ) ) :
					?>
					<h5 class="author-list-profile__job-title"><?php echo get_field( 'title', $author_meta_id ); ?></h5>
					<?php
				endif;
				?>
				<?php
				if ( ! empty( get_field( 'full_bio', $author_meta_id ) ) ) :
					?>
					<?php echo get_field( 'full_bio', $author_meta_id ); ?>
					<?php
				endif;
				?>
				<p class="author-list-profile__social">
					<?php
					if ( ! empty( get_field( 'instagram', $author_meta_id ) ) ) :
						?>
						<a href="http://instagram.com/<?php echo get_field( 'instagram', $author_meta_id ); ?>" target="_blank" class="icon-instagram" aria-label="Visit @<?php echo get_field( 'instagram', $author_meta_id ); ?>'s Instagram account">@<?php echo get_field( 'instagram', $author_meta_id ); ?></a>
						<?php
					endif;
					?>
					<?php
					if ( ! empty( get_field( 'twitter', $author_meta_id ) ) ) :
						?>
						<a href="http://twitter.com/<?php echo get_field( 'twitter', $author_meta_id ); ?>" target="_blank" class="icon-twitter">@<?php echo get_field( 'twitter', $author_meta_id ); ?></a>
						<?php
					endif;
					?>
					<?php
					if ( ! empty( get_field( 'email', $author_meta_id ) ) ) :
						?>
						<a href="mailto:<?php echo get_field( 'email', $author_meta_id ); ?>" class="icon-envelope"><?php echo get_field( 'email', $author_meta_id ); ?></a>
						<?php
					endif;
					?>
				</p>
			</div>
		</section>
	<?php endif; ?>
	</div>
	<div class="container">

		<section class="main">

			<section class="author__module-group"
				 data-module-init="ajax-load-more"
				 data-ajax-selector=".author__module-group"
				 data-ajax-child-selector=".author__author-list article"
				 data-ajax-button=".load-more .btn">

				<section class="author-list-title">
					<h2 class="module-heading"><?php the_author_meta( 'first_name', $author_id ); ?>'s Stories</h2>
				</section>

				<div class="author-list author__author-list">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
						<?php echo include_partial( 'post-list-card', array( 'name' => 'first' ) ); ?>
						<?php
					endwhile;
				endif;
				?>
				</div>
				<?php if ( $wp_query->max_num_pages > $current_page ) : ?>
					<div class="load-more">
						<a class="btn" href="<?php echo get_pagenum_link( $current_page + 1 ); ?>">Load More</a>
					</div>
				<?php endif; ?>
				
			</section>

			<aside class="sidebar author__sidebar">
				<?php
				the_module(
					'advertisement',
					array(
						'slots'     => array( 'rightrail' ),
						'page'      => 0,
						'iteration' => 0,
						'sticky'    => true,
					)
				);
				?>
			</aside>
		</section>
	</div>

</main>

<?php get_footer(); ?>
