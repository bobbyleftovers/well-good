<?php 
global $trend_index;
$trend_sponsor_label = $trend_sponsor_label ?? 'Paid content for:';
if($trend_sponsor_label == '') $trend_sponsor_label = 'Paid content for:';
$trend_sponsor = $trend_sponsor ?? false;
$trend_sponsor_link = $trend_sponsor_link ?? false;
$trend_title = $trend_title ?? false;
$add_mask = $add_mask ?? false;

if(!isset($trend_index) || $trend_index == 4) $trend_index = 0;
$trend_index++;
$cover_image = $cover_image ?? false;

if(isset($background_color_overwrite) 
  && $background_color_overwrite != null 
  && $background_color_overwrite !== 'default') {
    $trend_index = $background_color_overwrite;
}

global $bg_color;
if(  $background_color_overwrite === 'custom' ): 
  $bg_color = $background_color_overwrite_custom;
else:
  $bg_color = $colors[$post->ID]['trend-'.$trend_index];
endif;
$color_lightness = getColorLightness($bg_color);

if(!isset( $color_overwrite)) $color_overwrite = 'default';
$color_overwrite = $color_overwrite ?: 'default';
if($color_overwrite !== 'default'){
  $color = $color_overwrite === 'black' ? 'text-gray-dark': 'text-white';
} else if ($color_lightness === 'dark') {
  $color = 'text-white';
} else {
  $color = 'text-gray-dark';
}

$bg_class = "bg-trend-$trend_index--$post->ID";
if(isset($block['innerBlocks']) && is_array($block['innerBlocks'])) $last = end ( $block['innerBlocks'] );
else $last = false;

//Supress Margins when

$suppress_margin_top_when = array('trends-2021/trends-2021-advertisement', 'trends-2021/trends-2021-slideshow', 'trends-2021/trends-2021-spotlight');
$suppress_margin_bottom_when = array('acf/advertisement', 'acf/trend-spotlight');


?>

<?php if(!$prev || $prev['blockName'] !== 'trends-2021/trends-2021-spotlight'): ?>
<!-- end .container -->
</div>

<div class="clear"></div>
<?php endif; ?>

<!-- block -->
<div class="relative overflow-hidden trends-2021-spotlight__wrapper <?=$color?>
  <?php if(!$prev || !in_array($prev['blockName'], $suppress_margin_top_when)): ?><?=trends_2021_spacing('mt')?><?php endif; ?>
  <?php if(!$next || !in_array($next['blockName'], $suppress_margin_bottom_when)): ?><?=trends_2021_spacing('mb')?><?php endif; ?>
  <?php if($prev && isset($prev['blockName'])):?>prev-<?=$prev['blockName']?><?php endif;?> 
  <?php if($next && isset($next['blockName'])):?>next-<?=$next['blockName']?><?php endif; ?>
  <?php if($last): ?>last-<?=$last['blockName']?>  size-<?=sizeof($block['innerBlocks'])?><?php endif; ?>
  " 
  data-module-init="trends-2021-spotlight"
  style="opacity: 0"
  >
  <div  id="<?=$slug?>" class="trends-2021-spotlight__hash"></div>

    <!-- image -->
    <?php if ($cover_image): ?>
      <div class="trends-2021-spotlight__img-wrapper h-viewport absolute left-0 top-0 w-1/1 z-0">
        <div 
          class="trends-2021-spotlight__img absolute top-0 left-0 w-1/1 sm:w-1/2 h-1/1" 
          >
          <?php
          $img = wg_resize( $cover_image['url'], 721, 716, true, 85 );
          ?>
          <div class="bg-white opacity-30 trends-2021-spotlight__img__bg absolute top-0 left-0 w-1/1 h-1/1 bg-cover bg-no-repeat bg-center"
          data-src="<?=$img?>"
          >
            <?php if($add_mask): ?>
              <div class="trends-2021-spotlight__img__mask absolute top-0 left-0 w-1/1 h-1/1"></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!--<div class="trends-2021-spotlight__img__accent absolute z-10 <?=$bg_class?> block sm:hidden"></div>-->
    <?php endif; ?>
    <!-- end image -->

  <div 
    class="
      trends-2021-spotlight trends-2021-spotlight__trigger min-h-viewport 
      next-<?=$next['blockName']?>
      <?=$bg_class?> 
      <?= $cover_image ? '': 'trends-2021-spotlight--no-image'?> 
      <?=$color?>
    " 
    <?php if(  $background_color_overwrite === 'custom' ): ?>style="background-color:<?=$bg_color?>"<?php endif; ?>
  >
      
    <!-- content -->
    <div class="z-20 relative trends-2021-spotlight__content pb-e33 xs:pb-e37 md:pb-e55 ml:pb-e70">
      <div class="relative trends-2021-spotlight__heading <?= $cover_image ? 'transform -translate-y-1/2': '' ?>">
        <div class="container">
          <div class="text-label text-center mb-e12"><?=$pretitle ?? 'Trend Spotlight' ?></div>
          <?php if($trend_title): ?>
            <h2 class="trends-2021-spotlight__title text-center <?= $trend_sponsor ? 'mb-e10': 'mb-e20' ?>"><?=$trend_title?></h2>
          <?php endif; ?>
          <?php if($trend_sponsor): ?>
            <div class="text-link text-center mb-e20 mt-e20">
              <?= $trend_sponsor_label ?>
              <?php if($trend_sponsor_link): ?><a class="transition-opacity duration-300 hover:opacity-70 underline" href="<?=$trend_sponsor_link?>" target="_blank"><?php endif; ?>
                <?=$trend_sponsor?><?php if($trend_sponsor_link): ?>
              </a><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="z-10 relative trends-2021-spotlight__content__hidden">
          <div class="trends-2021-container">
            <?= $innerHTML ?>
          </div>
      </div>
    </div>
    <!-- end content -->

  </div>
</div>
<!-- end block -->

<?php if(!$next || $next['blockName'] !== 'acf/trend-spotlight'): ?>
<div class="clear"></div>

<!-- reset .container -->
<div class="trends-2021-container">

<?php endif; ?>

<?php $bg_color = false; ?>

<?php 
/* DON'T PURGE
next-acf\/trends-video 
next-core\/heading
*/
?>

