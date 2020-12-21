<?php

	/**
	 * Query posts for Editorial Tag Featured section
	 * @param object $editorialtag - current term
	 * @return array $posts - array of posts
	 */
	function get_editorialtag_featured_posts($editorialtag) {
		$field = get_field('editorialtag_featured', $editorialtag)['posts'];
		$max = 3;
		$terms = $editorialtag->term_id;

		if ($field != null && count($field) == $max) :
			$posts = $field;
		elseif ($field != null && count($field) != 0 && count($field) != $max) :
			$makeup = count($field) < $max ? $max - count($field) : 0;
			$posts = array_slice($field, 0, $max);
			if ($makeup) :
				$makeup_articles = get_posts(
					array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'posts_per_page' => $makeup,
						'post__not_in' => get_post_ids($field),
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field' => 'term_id',
								'terms' => $terms,
								'operator' => 'IN'
							)
						)
					)
				);
				foreach($makeup_articles as $makeup_article) :
					$posts[] = $makeup_article;
				endforeach;
			endif;
		else :
			$posts = get_posts(
				array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $max,
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $terms,
							'operator' => 'IN'
						)
					)
				)
			);
		endif;

		return array(
			'posts' => $posts
		);
	}