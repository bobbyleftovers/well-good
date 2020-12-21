<div class="modal modal-trends" data-module-init="modal-trends" data-open-elem=".js-open-modal-trends" data-close-elem=".modal-trends .js-modal-close, .modal-trends .js-modal-bg" data-next-elem=".js-modal-next" data-prev-elem=".js-modal-prev" data-article-elem=".js-modal-trends__article" data-container-elem=".js-modal-trends__container">
	<div class="modal-trends--outer">
		<span class="modal-trends-nav">
			<a href="#prev" class="modal-trends-prev js-modal-prev"><span class="h4 modal-trends-prev__label">PREV</span></a>
			<span class="modal-trends-prev-next-divider"></span>
			<a href="#next" class="modal-trends-next js-modal-next"><span class="h4 modal-trends-next__label">NEXT</span></a>
			<a href="#close" class="modal-close js-modal-close"></a>
		</span>
		<div class="modal-trends-content__wrapper">
			<div class="modal-trends-content__inner">
            	<div class="trend__image__wrapper">
            		<img src="" class="js-modal-trends__image" />
            		<img src="" class="js-modal-trends__image--full" />
					<p class="h6 article-card__image__credit js-modal-trends__image__credit"></p>
            	</div>
		        <div class="article-card__meta trend__meta">
					<span class="article-card__sponsored js-modal-trends__sponsored">
						<span class="h6 article-card__sponsor-link__by js-modal-trends__sponsor-link__by"></span>
						<img src="" class="article-card__sponsor-link__image js-modal-trends__sponsor-link__image" />
					</span>
					<a href="" target="_blank" class="article-card__sponsored js-modal-trends__sponsored-link">
						<span class="h6 article-card__sponsor-link__by js-modal-trends__sponsor-link__by"></span>
						<img src="" class="article-card__sponsor-link__image js-modal-trends__sponsor-link__image" />
					</a>
					<span class="h1 center-underline-sm-pink article-card--trend__number"><span class="js-modal-trends__number"></span></span>
					<h1 class="article-card--trend__title js-modal-trends__title"></h1>
					<div class="article-card--trend__image__content js-modal-trends__content post__wysiwyg"></div>
		            <div class="article-card__share modal-trends-content__share">
		            	<span class="h5 article-card--trend__social-share__label">Share</span>
		            	<a class="social-share__button article-card--trend__social-share__button social-share__button--facebook js-modal-trends__share__button--facebook" target="_blank" href="">
							<span class="icon-facebook"></span>
						</a>
						<a class="social-share__button article-card--trend__social-share__button social-share__button--twitter js-modal-trends__share__button--twitter" target="_blank" href="">
							<span class="icon-twitter"></span>
						</a>
						<a class="social-share__button article-card--trend__social-share__button social-share__button--pinterest js-modal-trends__share__button--pinterest" target="_blank" href="">
							<span class="icon-pinterest-p"></span>
						</a>
					    <a class="social-share__button article-card--trend__social-share__button social-share__button--flipboard js-modal-trends__share__button--flipboard" target="_blank" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title=<?= wg_esc_url(get_the_title()) ?>&amp;url=<?= urlencode(get_the_permalink()) ?>" data-flip-widget="shareflip">
					    	<span></span>
					    </a>
						<a class="social-share__button article-card--trend__social-share__button social-share__button--email js-modal-trends__share__button--email" href="">
							<span class="icon-paper-plane"></span>
						</a>
		            </div>
	        	</div>
			</div>
		</div>
	</div>
	<div class="modal-bg js-modal-bg"></div>
</div>