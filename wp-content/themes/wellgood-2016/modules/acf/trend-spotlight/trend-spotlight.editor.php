<?php
  // Get post ID
  $post_id = $_POST['post_id'];
  $trend_sponsor_label = $trend_sponsor_label ?? 'Paid content for:';
  if($trend_sponsor_label == '') $trend_sponsor_label = 'Paid content for:';
  $trend_sponsor = $trend_sponsor ?? false;
  $trend_sponsor_link = $trend_sponsor_link ?? false;
  $trend_title = $trend_title ?? false;

  //color overwrite
  if(!isset( $color_overwrite)) $color_overwrite = 'default';
  $color_overwrite = $color_overwrite ?: 'default';

  //bg overwrite
  $bg_index = '';
  if(isset($background_color_overwrite) 
  && $background_color_overwrite != null 
  && $background_color_overwrite !== 'default') {
    $bg_index = $background_color_overwrite;
  }

  // All colors
  $colors = array(
    '1' => get_field('color_trend_spotlight_1', $post_id),
    '2' => get_field('color_trend_spotlight_2', $post_id),
    '3' => get_field('color_trend_spotlight_3', $post_id),
    '4' => get_field('color_trend_spotlight_4', $post_id)
  );

  // Get computed lightness
  $lightness = array();
  foreach($colors as $key => $color) {
    $lightness[$key] = getColorLightness($color);
  }
?>
<div 
  class="acf-trend-spotlight my-e90 acf-trend-spotlight--<?=$post_id?> color-overwrite--<?=$color_overwrite?>" 
  data-bg-index="<?=$bg_index?>"
<?php if(  $background_color_overwrite === 'custom' ): ?>style="background-color:<?=get_field('background_color_overwrite_custom')?>"<?php endif; ?>
  data-bg-computed-index="<?=$bg_index && $bg_index != '' ? $bg_index : '1'?>"
  >
  <div class="py-e70 relative">
    <div class="text-label text-center mb-e12"><?=$pretitle ?? 'Trend Spotlight' ?></div>
    <?php if($trend_title): ?><h2 class="text-h1 text-center acf-trend-spotlight__title"><?=$trend_title?></h2><?php endif;?>
    <?php if($trend_sponsor): ?>
        <div class="text-link text-center mb-e20 mt-e20">
          <?=  $trend_sponsor_label ?>
          <?php if($trend_sponsor_link): ?><a href="<?=$trend_sponsor_link?>" target="_blank" class="inline-block ml-e5 mt-e1"><?php endif; ?>
            <?=$trend_sponsor?><?php if($trend_sponsor_link): ?>
          </a><?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="pt-e20">
      <InnerBlocks />
    </div>
  </div>
</div>

<style class="acf-trend-spotlight__style">
</style>

<script>

function trends2021SpotlightAssignIndexes(){
  var $elms = document.getElementsByClassName('acf-trend-spotlight');
  this.index = 1;
  for (var i = 0; i < $elms.length; ++i) {
    if($elms[i].dataset.bgIndex) this.index = $elms[i].dataset.bgIndex
    $elms[i].dataset.bgComputedIndex = this.index
    this.index++;
    if(this.index > 4) this.index = 1
  }
}

/* Function to add style */ 
function trends2021SpotlightAddStyle(styles) {   
  var className = 'acf-trend-spotlight__style';
  var $elms = document.getElementsByClassName(className);
  for (var i = $elms.length - 1; i >= 0; --i) {
    $elms[i].remove();
  }

  var css = document.createElement('style'); 
  css.type = 'text/css'; 
         
  if (css.styleSheet)  
    css.styleSheet.cssText = styles; 
     else  
  css.appendChild(document.createTextNode(styles)); 
  css.classList.add(className);
  document.getElementsByTagName("head")[0].appendChild(css); 
} 

clearTimeout(trends2021SpotlightTimeout)
var trends2021SpotlightTimeout = setTimeout(function(){
  var $elms = document.getElementsByClassName('acf-trend-spotlight');
  var styles = ''; 
  <?php
  foreach($colors as $key => $bg_color){ $color = ($lightness[$key] == 'dark' ? 'white': 'black');?>
    styles += ' .acf-trend-spotlight[data-bg-computed-index="<?= $key?>"] { background-color:<?=$bg_color?> }  '; 
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"], ';
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-expert-take, ';
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-expert-take *,';
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-stroke-text, ';
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-stroke-text * ';
    styles += ' { color:<?=$color?>; -webkit-text-stroke-color: <?=$color?>;}  '; 

    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-expert-take, ';
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-expert-take__label:before, '
    styles += ' .acf-trend-spotlight.color-overwrite--default[data-bg-computed-index="<?= $key?>"] .acf-expert-take__label:after' 
    styles += ' { color:<?=$color?>; border-color: <?=$color?>;} ';
  <?php }
  ?>

  trends2021SpotlightAddStyle(styles);
  new trends2021SpotlightAssignIndexes();
}, 200)
</script>