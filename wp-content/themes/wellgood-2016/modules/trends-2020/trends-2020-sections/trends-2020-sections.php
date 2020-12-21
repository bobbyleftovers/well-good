<?php 

$sections = get_field('sections');

foreach ($sections as $section):
	if(isset($section['visible']) && $section['visible']): 
		$slug = str_replace("_", "-", $section['acf_fc_layout'])
	?>

	<div class="trends-2020__section trends-2020__<?= $slug ?>">
		<div class="container">
		<?php
			$section['module_name'] = 'trends-2020-'.$slug;
			include_module('trends-2020/'.$section['module_name'], $section);
		?>
		</div>
	</diV>

<?php 
  endif;
endforeach; 
?>

<div style="display:block; width:100%; height:1px; clear:both; float:none;"></div>