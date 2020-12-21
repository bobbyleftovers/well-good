<?php 
global $trend_hub_ad_index;

// @WORK
// Temporary static limit to 5 ads.
// Christina wants to limit the amount of ads
// to 5
if ($trend_hub_ad_index < 5) :
	the_module('advertisement', array(
		'class' => array(
			'trends-2020-adverstisement',
			'waypoint'
		),
		'slots' => array(
			'inline',
			'slot'
		),
		'page' => 0,
		'iteration' => $trend_hub_ad_index
	)); 

	$trend_hub_ad_index++; 
endif;