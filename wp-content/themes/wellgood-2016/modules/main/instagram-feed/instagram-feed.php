<?php

$handle = get_field('instagram_handle', 'options');

?>

<section class="instagram-feed">

  <div class="instagram-feed-container" data-module-init="instagram-feed">

    <span class="instagram-feed-follow">
      <a href="https://www.instagram.com/<?= $handle; ?>" target="_blank" class="instagram-feed-follow-link" aria-label="Follow us on Instagram: @<?= $handle; ?>">
        <span class="text-gray instagram-feed-follow-icon icon-instagram"></span>
        <h3 class="text-gray instagram-feed-follow-label">Follow us</h3>
        <p class="text-gray instagram-feed-follow-handle">@<?= $handle; ?></p>
      </a>
    </span>

    <ul class="instagram-feed-list">
      <li v-for="post in posts">
        <a :href="post.url" target="_blank" class="instagram-feed-image-link">
          <span class="instagram-feed-image-wrapper">
            <div class="image-module"
              :style="'background-image: url('+post.thumnbail+'); opacity: 1;'">
            <img
              class="image-module-img"
              :src="post.thumnbail"
              :data-src="post.thumnbail"
              :data-src-retina="post.thumnbail"
              :alt="post.caption">
        </div>
          </span>
        </a>
      </li>
    </ul>-->

  </div>

</section>
