<?php
$coming_soon_copy = (isset($coming_soon_copy)) ? $coming_soon_copy : null;
$coming_soon_date = (isset($coming_soon_date)) ? $coming_soon_date : null;?>

<div class="renew-year-2021-posts__inactive flex flex-col justify-center items-center relative overflow-hidden bg-<?=$post->ID?> text-white">
	<div class="renew-year-2021-posts__overlay opacity-40 absolute top-0 bottom-0 left-0 right-0 z-40">&nbsp;</div>
	<div class="flex relative z-30 justify-center items-center w-full h-full"><?php
		if(isset($image)):
			$path_id = rand(1, 1000);
			$path_id = md5($path_id);?>

			<figure class="renew-year-2021-posts__inactive-figure renew-year-2021-posts__inactive-figure--<?= $type ?> m-0 absolute <?= $type === 'keyhole' ? 'md:top-e20 md:w-1/3' : 'md:top-e55 md:w-1/3' ?>">
				<svg class="svg-frame" viewBox="<?= $viewboxes[$type] ?>" fill="red" xmlns="http://www.w3.org/2000/svg">
					<clipPath id="frame-<?= $path_id ?>"  class="svg-frame__clip-path">
						<path fill-rule="evenodd" clip-rule="evenodd" d="<?= $clip_paths[$type] ?>" stroke="transparent"/>
					</clipPath>
					<image clip-path="url(#frame-<?= $path_id ?>)" class="svg-frame__img h-full w-full" xlink:href="<?= $image['url'] ?>" preserveAspectRatio="xMidYMin slice"></image>
				</svg>
			</figure><?php
		endif;?>
	</div>
	<!-- text -->
	<div class="renew-year-2021-posts__text text-center relative z-50 py-e130 flex flex-col items-center justify-center w-1/3">
		<?= ($coming_soon_copy && $coming_soon_date) ? '<span class="text-tag mb-e10">'.$coming_soon_copy.' '.$coming_soon_date.'</span>' : null;?>
		<h2 class="page-title"><?= $title ?></h2>
		<div class="text-default text-center"><?= $description ?></div>
	</div>
</div>