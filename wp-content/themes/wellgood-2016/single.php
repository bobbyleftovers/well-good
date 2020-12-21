<?php
  if(is_archive()):
	header('HTTP/1.0 302 Found');
	header('Location: http://www.wellandgood.com');
  endif;

  get_header();

  get_template_part( 'templates/post' );

  get_footer();
?>
