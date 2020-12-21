<?php
  // Var
  $video = $video ?? array();
  $separator = $separator ?? 'More';
  $title = $title ?? false;
  $text = $text ?? false;
  $video['class'] = 'bg-tan';
  $channel_card = $channel_card ?? false;
  if(!$posts || !is_array($posts)) $posts = array();
  $posts_length = sizeof($posts);
  $length = $posts_length;
  if($channel_card && (!$channel_card['image'] || !$channel_card['url'])){
    $channel_card = false;
  } else {
    $channel_card['cta'] = $channel_card['cta'] ?? 'View all videos';
    $channel_card['image'] = $channel_card['image']['sizes']['youtube-channel-card'];
  }

  // CTA card injection
  $injections = array();
  if ($channel_card && $channel_card['active']):
    $channel_card['module'] = 'main-2020/cta-card';
    $injections[] = $channel_card;
  endif;
  if(sizeof($injections)) $length += sizeof($injections);

  // Layout
  if($length <= 3) {
    $layout = 'generic';
    $posts = array_merge(array($video), $posts);
    if ( $posts_length == 2) $injections = array();
  } else {
    $layout = 'main';
    if ( $posts_length < 5) $injections = array();
  }

  if(!isset($social_share)) $social_share = true;

?>

<div class="rich-tag-yt bg-tan-light <?=$class?>">
  <div class="container">

    <!-- Text -->
    <?php if ($title && $title != ''): ?>
    <div class="flex flex-wrap -mx-gutter1/2 mb-e25 md:mb-e30">
      <div class="w-full ml:w-2/3 px-gutter1/2 text-gray">
        <h2 class="text-h2 relative mt-0 mb-e8 md:mb-e10">
          <?= $title ?>
          <?= $anchor ?>
        </h2>
        <?php if ($text && $text != ''): ?>
          <div class="text-big"><?= $text ?></div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- generic layout -->
    <?php if($layout == 'generic'):
      brrl_the_module('main-2020/posts-smart-grid',array(
        'posts' => $posts,
        'injections' => $injections,
        'social_share' => $social_share
      ));
    ?>

    <!-- video specific layout -->
    <?php else: ?>
      
      <div class="items-stretch flex flex-wrap -mx-gutter1/2">
        <div class="px-gutter1/2 mb-e20 md:mb-0 lg:mb-e10 w-full flex items-stretch">
            <?php brrl_the_module('main-2020/video', $video); ?>
        </div>
      </div>

      <?php
        brrl_the_module('main-2020/separator', $separator);

        brrl_the_module('main-2020/posts-smart-grid', array(
          'posts' => $posts,
          'injections' => $injections,
          'social_share' => $social_share,
          'wrapper_class' => 'mt-e35 mt:mb-0'
        ));
      ?>
    <?php endif; ?>

  </div>
</div>
