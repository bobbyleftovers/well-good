<div class="social-share">
	<a class="social-share__button social-share__button--facebook" target="_blank" href="//www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_the_permalink()) ?>" data-vars-event="Facebook" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
		<span class="icon-facebook"></span> Share
	</a>
	<a class="social-share__button social-share__button--twitter" target="_blank" href="//twitter.com/share?text=<?= wg_esc_url(get_the_title() . ' via ' . get_twitter_handle()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-vars-event="Twitter" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
		<span class="icon-twitter"></span> Tweet
	</a>
	<a class="social-share__button social-share__button--pinterest" target="_blank" href="//pinterest.com/pin/create/link/?url=<?= urlencode(get_the_permalink()) ?>&amp;description=<?= wg_esc_url(get_the_title()) ?>&amp;media=<?= urlencode( get_the_post_thumbnail_url(get_the_ID(), 'medium') ); ?>" data-vars-event="Pinterest" data-vars-info="<?= esc_url($_SERVER['REQUEST_URI']) ?>">
		<span class="icon-pinterest-p"></span> Pin It
	</a>
</div>
