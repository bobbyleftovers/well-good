<?php if( has_shortcode( get_the_content(), 'location_hub_link' )) : ?>
<div class="modal modal-maps" data-module-init="modal-maps" data-open-elem=".js-open-modal-maps" data-close-elem=".modal-maps .js-modal-close, .modal-maps .js-modal-bg" data-app-elem=".js-modal-map-app">
	<div class="modal-maps--outer">
		<div class="modal-maps-content__wrapper">
			<div class="modal-maps-content__inner">
				<div class="modal-maps-close">
					<h6 class="modal-maps-close__note js-modal-close">close</h6>
					<a href="#close" class="modal-close js-modal-close"></a>
				</div>
            	<div class="js-modal-map-app">
					
					<div class="skeleton-container">
						<div class="skeleton-cards">
							<div class="skeleton-header"></div>
							<div class="skeleton-cards__wrapper">
								
								<!-- begin loop -->
									<?php for( $i = 1; $i < 6; $i++) { ?>
										<div class="skeleton-card">
											<div class="skeleton-content">
												<div class="skeleton-animation"></div>
												<div class="skeleton-content__meta">
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo skeleton-content__pseudo--short"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white tall"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white noheight noheight-bottom"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white med"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white tall"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white tall"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white"></span>
													</div>
													<div class="skeleton-row">
														<span class="skeleton-content__pseudo"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white tall"></span>
														<span class="skeleton-content__pseudo skeleton-content__pseudo--white noheight noheight-top"></span>
													</div>
												</div>
												<div class="skeleton-content__image"></div>
												<div class="skeleton-content__icon"></div>
											</div>
										</div>
									<?php }; ?>
								<!-- end loop -->

							</div>
						</div>
						<div class="skeleton-map"></div>
					</div>

            	</div>
			</div>
		</div>
	</div>
	<div class="modal-bg js-modal-bg"></div>
</div>
<?php endif; ?>