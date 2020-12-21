<?php

$experts_tax = get_the_terms( $id, 'experts' );
$experts     = $experts_tax ? array_map(
	function ( $experts ) {
		$term_tax = $experts->taxonomy;
		$term_id  = $experts->term_id;
		$term     = get_term( $term_id, $term_tax );

		return array(
			'name'  	  => $term->name,
			'slug'			=> $term->slug,
			'title' 		=> get_field( 'expert_title', "{$term_tax}_{$term_id}" ),
			'bio'   		=> $term->description,
			'short_bio' => get_field( 'expert_short_bio', "{$term_tax}_{$term_id}" ),
			'url'   		=> get_field( 'expert_url', "{$term_tax}_{$term_id}" ),
			'image' 		=> get_field( 'expert_image', "{$term_tax}_{$term_id}" ),
		);
	},
	$experts_tax
) : array();

if ( ! empty( $experts ) ) : ?>
  <div class="text-center mt-e50 border-t-1 border-b-1 border-solid border-seafoam-dark">
		<div class="text-label text-seafoam-dark block mb-e20 -mt-e6 lg:-mt-e9">
			<span class="px-e20 bg-white inline">Experts Referenced</span>
		</div>
		<div class="flex flex-col">
			<?php foreach ( $experts as $expert ) :
				$link = get_permalink( get_page_by_path( 'experts' ) ) . "?expert={$expert['slug']}"; ?>

				<div class="flex flex-col mb-e30">
					<div class="text-h5 mb-e6"><a href="<?= $link; ?>" class="hover-underline-seafoam-dark no-underline border-0 inline-block leading-snug"><?= $expert['name']; ?></a></div>
					<div class="text-small italic"><a href="<?= $link; ?>" class="no-underline border-0"><?= $expert['title']; ?></a></div>
				</div>
			<?php endforeach; ?>
		</div>
  </div>
<?php endif; ?>
