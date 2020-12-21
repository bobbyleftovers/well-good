<?php

/**
 * Query posts for Editorial Tag Preview section
 * @param object $editorialtag - current term
 * @return array $posts - array of post objects
 */
function get_editorialtag_preview_posts($editorialtag) {
	$posts = get_posts(
		array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'tax_query' => array(
				'relation' => 'IN',
				array(
					'taxonomy' => 'category',
					'field' => 'term_id',
					'terms' => $editorialtag->term_id,
				)
			)
		)
	);

	return $posts;
}