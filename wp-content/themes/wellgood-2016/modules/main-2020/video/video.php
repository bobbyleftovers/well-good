<?php
  $class = $class ?? '';
  $oembed = $oembed ?? '';
  $crop = $crop ?? false;
  $width = $width ?? 1440;
  $height = $height ?? 810;
  $image = $image ?? false;
  $thumbnail = $thumbnail ?? false;
  $date = $date ?? false;
  $caption = $caption ?? false;
  $src = $src ?? false;
  $provider = null;
  $thumbnail_size = $thumbnail_size ?? ($crop ? 'full':'article-retina');

  if($image && is_array($image) && $image['sizes']){
    if($thumbnail_size === 'full') $thumbnail = $image['url'];
    else $thumbnail = $image['sizes'][$thumbnail_size];
  }

  if($crop){
    $thumbnail = wg_resize( $thumbnail, $width, $height, true, 85 );
  } 

  if($oembed && !$src){
    preg_match('/src="(.+?)"/', $oembed, $matches);
    $src = $matches[1];
  }

  if($src){
    $video = new WG\Utils\Video_Oembed($src);
    $video_data = @$video->get_data();
    $provider = $video_data['provider'];
  }

  if($provider === 'youtube'){
    $metadata = $video_data['metadata'];
    $thumbnail = $thumbnail ? $thumbnail : $metadata['thumbnails']['maxresdefault'];
    $caption = $caption ? $caption : $metadata['name'];
    $date = $date ? $date : $metadata['uploadDate'];
  }

  $date = $date ? date('F d, Y',strtotime($date)) : false;
  $caption = $caption ? wp_trim_words($caption, 15) : false;

?>
<div class="w-full video relative overflow-hidden <?= $class ?>" <?php if($provider === 'youtube'): ?> data-module-init="video" <?php endif; ?>>
  <div class="w-full pb-16/9"></div>
  <div class=" group cursor-pointer">
    <?php if($provider == 'youtube'): ?>
      <div class="absolute-full">
        <div class="video__iframe" data-id="<?=$video_data['id']?>"></div>
        <div class="video__cover absolute-full bg-center bg-no-repeat bg-cover" style="background-image:url(<?=$thumbnail?>);">
          <?php if($caption): ?>
            <div class="video__mask video__caption-mask absolute w-full h-full top-0 left-0"></div>
            <div class="video__caption-wrapper absolute bottom-0 left-0 p-e20 md:p-e22 lg:p-e30 text-white">
              <?php if ($date): ?>
              <div class="text-tag mb-e8">
                <?= $date ?>
              </div>
              <?php endif; ?>
              <div class="video__caption text-h5"><?= $caption ?></div>
            </div>
          <?php endif; ?>
          <div class="absolute-center">
            <?php brrl_the_module('main-2020/base-button-play'); ?>
          </div>
        </div>
      </div>
      <?php else: ?>
      <?= $oembed; ?>
    <?php endif; ?>
  </div>
</div>
