<?php
/**
 * Editorial Tag Page - Main Grid
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


global $editorialtag_ad_index, $editorialtag_ad_max;

$args = isset( $post->editorialtag_grid_field ) ? $post->editorialtag_grid_field : '';

$articles = $args['articles'];
$class = $args['class'] ?? '';
$posts_per_group = $args['posts_per_group'];
$title = array_key_exists( 'title', $args ) ? $args['title'] : '';
$article_count = count( $articles );
$row_count = ( $class == 'featured' ) ? 3 : 4;

if ( $articles ) :

	if ( $title ) : ?>
		<div class="container">
			<h2 class="editorialtag-section__header">
				<?= $title; ?>
			</h2>
		</div>
	<?php 
	endif; ?>

	<div class="container">
		<section>
			<div class="editorialtag-grid">
				<?php
				foreach ( $articles as $key => $article ) :

					echo include_partial( 'editorialtag-grid-card', array( 
						'class' => $class,
						'article' => $article
					) ); 

					if ( ( $key + 1 ) % $posts_per_group == 0 && $editorialtag_ad_index < $editorialtag_ad_max ) : ?>
						<div class="editorialtag-grid__ad">
							<?php the_module( 'advertisement', array(
								'slots' => array(
									'inline',
									'slot'
								),
								'page' => 0,
								'iteration' => $editorialtag_ad_index
							) ); $editorialtag_ad_index++; ?>
						</div>
					<?php
					endif;

				endforeach; 

				if ( ( $class == 'recent' || $class == 'featured' ) && $article_count % $row_count !== 0 ) :
					$blanks = $row_count - ( $article_count % $row_count );
					$blank_class = $class == 'featured' ? 'editorialtag-grid-card--featured-blank' : 'editorialtag-grid-card--blank';

					for ( $x = 0; $x < $blanks; $x++ ) :
						echo "<div class=\"editorialtag-grid-card $blank_class\"></div>";
					endfor;

				endif; ?>
			</div>
		</section>
	</div>

<?php
endif; ?>