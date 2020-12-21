<template type="amp-mustache" id="relatedContentCard">
    <div class="related-content__card">
      <a href="{{url}}" class="related-content__link related-content__image" ignore-nofollow>
        <amp-img layout="fill" src="{{image_url}}" />
      </a>
      <div class="related-content__card--content">
        <a href="{{url}}" class="related-content__link" ignore-nofollow>
          <div class="related-content__card--title">{{ title }}</div>
        </a>
      </div>
    </div>
  </template>
  <div class="related-content">
    <h4 class="related-content__title">Related Stories</h4>
    <amp-list
      template="relatedContentCard"
      height="165"
      width="auto"
      id="amp-related-content"
      layout="fixed-height"
      credentials="include"
      src="/wp-json/api/v1/parsley/related?limit=10&pub_date_start=<?= $post_date->format('Y-m-d'); ?>&url=<?= $url ?>&image=<?= $featured_image ?>&title=<?= sanitize_title(get_the_title()) ?>&apikey=<?= $apikey ?>&secret=<?= $secret ?>&RANDOM"
      binding="no"
      items="."
      max-items="2"
    >
    </amp-list>
  </div>