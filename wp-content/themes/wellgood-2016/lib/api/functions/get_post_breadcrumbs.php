<?php

/**
 * 
 */
function get_post_breadcrumbs( $id ) {
	$current_page = get_page_type( $id, array(
		'return_data' => TRUE 
	) );

	$list_items = array();
	switch ( $current_page['pagetype'] ) :
		case 'article' :
			$hero_tag = get_field('hero_tag', $id) 
				? get_term(get_field('hero_tag', $id), 'category') 
				: NULL;
		
			if ( ! isset( $hero_tag ) || empty( $hero_tag ) ) :
				return;
			endif;
		
			array_push($list_items, $hero_tag->term_id);
		
			for ($i = 0; $i < get_category_depth( $hero_tag ); $i++) :
				array_unshift($list_items, get_term($list_items[0], 'category')->parent);
			endfor;
		break;

		case 'category' :
			array_push($list_items, $current_page['object']->term_id);

			for ($i = 0; $i < get_category_depth( $current_page['object'] ); $i++) :
				array_unshift($list_items, get_term($list_items[0], 'category')->parent);
			endfor;
		break;

		default :
			return;

	endswitch;

	$position = 1;
	$breadcrumbs = array(
		'@context' => 'https://schema.org',
		'@type' => 'BreadcrumbList',
		'itemListElement' => array(
			array(
				'@type' => 'ListItem',
				'position' => $position,
				'name' => 'Well + Good',
				'item' => get_site_url()
			)
		)
	);
	
	foreach($list_items as $item) :
		$obj = get_term($item, 'category');

		if ($obj) :
			array_push($breadcrumbs['itemListElement'], array(
				'@type' => 'ListItem',
				'position' => ++$position,
				'name' => $obj->name,
				'item' => get_category_link($obj->term_id)
			));
		endif;
	endforeach;

	if ( $breadcrumbs ) :
		return json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES);
	endif;

	return;
}
