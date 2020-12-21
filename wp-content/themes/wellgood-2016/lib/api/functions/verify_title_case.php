<?php

use WG\Content\Title;

function verify_title_case( $title, $date = NULL, $override = FALSE ) {
	return Title::verify_title_case( $title, $date, $override );
}