<?php
  $args = [];
  $menu_class = '';
  $li_class = '';
  $a_class = '';
  $label_class = '';

  if (!empty($post->social_media_links_field)) {
    $args = $post->social_media_links_field;
    $menu_class = array_key_exists('menu_class', $args) ? $args['menu_class'] : '';
    $li_class = array_key_exists('li_class', $args) ? $args['li_class'] : '';
    $a_class = array_key_exists('a_class', $args) ? $args['a_class'] : '';
    $label_class = array_key_exists('label_class', $args) ? $args['label_class'] : '';
  }

	if( have_rows('social_media_links', 'options') ): ?>
	<div class="menu-social-menu-container">
	    <ul class="menu flex justify-start items-center <?= $menu_class ?>">

		<?php while( have_rows('social_media_links', 'options') ): the_row(); ?>
	        <li class="menu-item menu-item-type-social <?= $li_class ?>"><a href="<?= get_sub_field('url'); ?>" class="<?= get_sub_field('class'); ?> <?= $a_class ?>" target="_blank"><span class="label <?= $label_class ?>"><?= get_sub_field('label'); ?></span></a></li>
	    <?php endwhile; ?>
	    </ul>
	</div>
<?php
	endif;
?>
