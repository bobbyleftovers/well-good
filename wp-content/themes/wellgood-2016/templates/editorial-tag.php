<?php

global $editorialtag_ad_index, $editorialtag_ad_max;

$category = get_queried_object();
$category_id = $category->term_id;
$all_categories = get_categories(
  array(
	'taxonomy' => 'category',
	'hide_empty' => false
  )
);

$editorialtag_ad_max = 5;
$editorialtag_ad_index = 0;

$posts_per_group = 12;
$post_count_older = 12;
$post_group_count = 6;

$posts_per_page = ($posts_per_group * $post_group_count) + $post_count_older;
$subhead = array_map(function($cat) {
  return $cat->term_id;
}, array_filter($all_categories, function($cat) use($category_id) {
	$parent_id = $cat->category_parent;
	return $category_id == $parent_id;
}));

$more_posts_title = get_field('editorialtag_recent_title', $category) ?? 'Most Recent';
$featured_title = get_field('editorialtag_featured', $category)['title'] ?? 'Featured';

$terms = !empty($subhead)
	? array_merge($subhead, array($category_id))
	: $category_id;
$featured = get_editorialtag_featured_posts($category);
$module_data = get_field('editorialtag_modules', $category);
if ($module_data) :
	$modules = array_map( function( $module ) use( $category_id ) {
		$shared_args = array(
			'category_id' => $category_id,
			'title' => $module['title'],
		);

		switch ($module['acf_fc_layout']) :
			case 'editorialtag_module_full' :
				$args = array_merge( $shared_args, array(
					'key' => 'full',
					'title' => $module['title'],
					'posts' => $module['posts']
				) );
				break;

			case 'editorialtag_module_half' :
				$args = array_merge( $shared_args, array(
					'key' => 'half',
					'posts' => $module['posts'],
					'background_image' => $module['background_image']
				) );
				break;

			case 'editorialtag_module_videos' :
				$args = array_merge( $shared_args, array(
					'key' => 'videos',
					'posts' => $module['posts'],
				) );
				break;

			case 'editorialtag_module_tag' :
				$tag = get_term($module['tag']);
				$args = array_merge( $shared_args, array(
					'key' => 'tag',
					'tag' => $tag,
					'posts' => get_editorialtag_preview_posts($tag)
				) );
				break;

			case 'editorialtag_module_post' :
				$args = array_merge( $shared_args, array(
					'key' => 'post',
					'posts' => $module['post'],
					'sponsored' => $module['sponsored'],
				) );
				break;

		endswitch;

		return $args;
	}, (array) $module_data);
else :
	$modules = NULL;
endif;

$featured_posts = $featured['posts'] ? $featured['posts'] : array();
$module_posts = $module_data ? array_flatten(
	array_map(function($module) {
		return $module['posts'];
	}, $modules)
) : array();
$editorialtag_args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => $posts_per_page,
	'order' => 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'category',
			'field' => 'term_id',
			'terms' => $terms,
			'operator' => 'IN',
		)
	),
	'post__not_in' => array_map(function($data) {
			return $data->ID;
		}, array_merge(
			$featured_posts,
			$module_posts
		)
	)
);

$editorialtag_query = new WP_Query($editorialtag_args);
$editorialtag_posts = $editorialtag_query->posts;

$total = count($editorialtag_posts);
$max = intval($editorialtag_query->max_num_pages);
?>

<main class="editorialtag-page wg__inline-ad-wrapper loading" data-module-init="editorialtag-page">
	<?php
	the_module('editorialtag-hero', array(
		'category' => $category,
		'subhead' => $subhead
	));

	the_module('editorialtag-grid', array(
		'title' => $featured_title,
		'articles' => $featured['posts'],
		'class' => 'featured',
		'posts_per_group' => $posts_per_group
	)); ?>

	<?php if ($editorialtag_ad_index < $editorialtag_ad_max) : ?>
		<div class="editorialtag-grid__ad">
			<?php the_module('advertisement', array(
				'slots' => array(
					'inline',
					'slot'
				),
				'page' => 0,
				'iteration' => $editorialtag_ad_index++
			)); ?>
		</div>
	<?php endif; ?>

	<?php
	if ($module_data) :
		$overflow_title = '';
		foreach ($modules as $i => $module_args) :
			$end = count($modules) == $i + 1;
			$title = $i == 0 ? $more_posts_title : '';

			the_module('editorialtag-grid', array(
				'title' => $title,
				'articles' => array_splice($editorialtag_posts, 0, $posts_per_group),
				'posts_per_group' => $posts_per_group
			));
			the_module('editorialtag-module-' . $module_args['key'], $module_args);
		endforeach; ?>
	<?php
	else :
		$overflow_title = $more_posts_title;
	endif; ?>

	<?php
	if ($total > ($posts_per_page - $posts_per_group)) :
		$older_posts = array_splice($editorialtag_posts, ($post_count_older * -1), $post_count_older);
	else :
		$older_posts = null;
	endif; ?>

	<?php
	the_module('editorialtag-grid', array(
		'title' => $overflow_title,
		'articles' => $editorialtag_posts,
		'class' => 'recent',
		'posts_per_group' => $posts_per_group
	));
	if ($older_posts) :
		the_module('editorialtag-older', array(
			'articles' => $older_posts,
		));
	endif; ?>

</main>
<?php the_module('editorialtag-share'); ?>
