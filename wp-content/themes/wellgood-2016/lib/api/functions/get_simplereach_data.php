<?php
/**
 * Get SimpleReach Tracking Data
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 15.0.1
 */

function get_simplereach_data( $post_id ) {


	$sr_url = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$sr_url = explode( '?', $url )[0];

	$sr_channels = array_map( function( $category ) {
		return $category->name;
	}, list_parent_categories( get_the_category() ) );

	$sr_tags = array_map( function( $tag ) {
		return $tag->name;
	}, get_the_tags() );

	$sr_config_values = array(
		'pid' => get_field( 'simplereach_pid_code', 'options' ),
		'url' => $sr_url,
		'title' => $post->post_title,
		'date' => get_the_date(),
		'authors' => array( get_the_author() ),
		'channels' => $sr_channels,
		'tags'  => $sr_tags,
		'article_id' => $post_id,
		'ignore_errors' => false,
		'manual_scroll_depth' => false,
  );
  
  return $sr_config_values;
}