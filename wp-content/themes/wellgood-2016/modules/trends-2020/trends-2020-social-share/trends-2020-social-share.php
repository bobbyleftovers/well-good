<?php

	$description = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true) | get_the_excerpt();
	$title = get_post_meta(get_the_ID(), '_yoast_wpseo_title', true) | (get_the_title(). ' | Well+Good');

	$social_share = array(
		array(
			'href' => "//www.facebook.com/sharer/sharer.php?u=". urlencode(get_the_permalink()),
			'handle' => "facebook",
			'icon' => 'facebook'
		),
		array(
			'href' => "//twitter.com/share?text=". wg_esc_url(get_the_title() . ' via ' . get_twitter_handle()) ."&amp;url=". urlencode(get_the_permalink()),
			'handle' => "twitter",
			'icon' => 'twitter'
		),
		array(
			'href' => "mailto:?subject=". wg_esc_url( $title ) ."&body=". wg_esc_url( $description . "\n\n" . get_the_permalink() ),
			'handle' => "email",
			'icon' => 'link'
		)
	)

	?> <div class="trends-2020-social-share"> <?php

	foreach($social_share as $button):

		?>
			<a 	class="trends-2020-social-share__button trends-2020-social-share__button--<?= $button['handle'] ?>" 
					target="_blank" 
					href="<?= $button['href'] ?>" 
					data-vars-event="<?= ucfirst($button['handle']) ?>" 
					data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
					<span class="icon-<?= $button['icon'] ?>"></span>
			</a>

		<?php

	endforeach;

	?> </div> 