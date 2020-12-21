<?php
global $post;
global $trend_index;

$id_str = rand(5, 1000);
$id_str = md5($id_str);

$id_str = rand(5, 1000);
$id_str = md5($id_str);
$index = (!isset($trend_index)) ? 1 : $trend_index + 1;
$index = ($trend_index === 4) ? 1 : $index;
$next = $next ?? false;
$bg_color = $colors[$post->ID]['trend-'.$index];
$split_hex_color = str_split( substr($bg_color, 1), 2 );
$rgb = hexdec( $split_hex_color[0] ).', '.hexdec( $split_hex_color[1] ).', '.hexdec( $split_hex_color[2] );
$clip_paths = [
  'M280 193.472C280 300.324 217.32 386.944 140 386.944C62.6801 386.944 0 300.324 0 193.472C0 86.6205 62.6801 0 140 0C217.32 0 280 86.6205 280 193.472Z', // oval
  'M280.227 386.946H0.226562V125.094H0.377844C3.80714 55.4646 65.1078 0 140.227 0C215.345 0 276.646 55.4646 280.075 125.094H280.227V386.946Z', // keyhole
  'M0 110C0 49.2488 49.2487 0 110 0H170C230.751 0 280 49.2487 280 110V274.276C280 335.027 230.751 384.276 170 384.276H110C49.2487 384.276 0 335.027 0 274.276V110Z'  // rounded rectangle
];
$sm_clip_path = 'M197.53 120.5C197.53 187.05 153.311 241 98.7648 241C44.2185 241 0 187.05 0 120.5C0 53.9497 44.2185 0 98.7648 0C153.311 0 197.53 53.9497 197.53 120.5Z';
$frame_index = 1;
$format = $format ?? 'large';
if(isset($block['parentBlock']) && $block['parentBlock']) $format = 'small';
$viewbox = ($format === 'large') ? '0 0 281 387' : '0 0 198 241';
$size = sizeof($slideshow);
$copy_color_class = 'text-gray';

// determine background color and build edge gradients (if needed)
if ($parentBlock) {
  $index = (!isset($trend_index)) ? 1 : $trend_index + 1;
  $index = ($trend_index === 4) ? 1 : $index;
  $background_color = $colors[$post->ID]['trend-'.$index];
  $color_lightness = getColorLightness($background_color);

  if ($color_lightness === 'dark') {
    $copy_color_class = 'text-white';
  }
} else {
  $background_color = $colors[$post->ID]['body-background'];
}

$split_hex_color = str_split( substr($background_color, 1), 2 );
$rgb = hexdec( $split_hex_color[0] ).', '.hexdec( $split_hex_color[1] ).', '.hexdec( $split_hex_color[2] );
$figure_classes = ($format === 'large') ? 'mb-e28 sm:mb-e0' : 'px-e18 sm:px-e9 ml:px-e13';
$svg_classes = ($format === 'large') ? 'mb-e18 sm:mb-e20 ml:mb-24 lg:mb-e12' : 'mb-e9 sm:mb-e17 ml:mb-e24 lg:mb-e33';
$caption_classes = ($format === 'large') ? '' : '';
$spacing_bottom_small = 'pb-e40 md:pb-e60 lg:pb-e80';
$spacing_bottom_large = $next && $next['blockName'] === 'acf/trend-spotlight' ? 'pb-e30 lg:pb-e50' : 'pb-e30';
$spacing_top = 'pt-e20 md:pt-e50 ml:pt-e55 lg:pt-e40';

// !NO Purge CSS
// trends-2021-slideshow--large trends-2021-slideshow--small

?>

<!-- end container and clear content -->
</div>
<div class="clear"></div>

<div class="<?= $spacing_top?> <?= $format === 'small' ? $spacing_bottom_small : $spacing_bottom_large ?>">

  <div id="slider-<?= $id_str ?>" 
    class="trends-2021-slideshow trends-2021-slideshow--<?= $format ?>" 
    data-slideshow-size="<?= $size ?>" 
    data-format="<?= $format ?>" 
    style="background: <?= $color_body ?>" 
    data-module-init="trends-2021-slideshow" ref="wrapper"><?php
    if ($size > 4 && $format === 'small') {?>
      <div class="trends-2021-slideshow__edge trends-2021-slideshow__edge--left"></div>
      <div class="trends-2021-slideshow__edge trends-2021-slideshow__edge--right"></div><?php
    }?>
    <div class="w-1/1 trends-2021-slideshow__container" ref="slideshow__container">
      <?php foreach($slideshow as $image):

        // ensure clipping path id is unique
        $path_id = rand(5 * $frame_index, 1000);
        $path_id = md5($path_id);
        $fill_class = ($frame_index === 1) ? 'fill-primary--'.$post->ID : 'fill-accent-'.$frame_index.'--'.$post->ID;
        
        $deviation = 0;

        if($format === 'large') {
          switch ($frame_index) {
            case 1:
              $deviation = 25;
              break;
            case 2:
              $deviation = 25;
              break;
            case 3:
              $deviation = 25;

          }
        }
       
        // Cell
        include 'trends-2021-slideshow.cell.php';

        // increment which clipping path to use (only used for large slider)
        if ($format === 'large') {
          if($frame_index === 3) {
            $frame_index = 1;
          } else {
            $frame_index++;
          }
        }
      endforeach; ?>
    </div>
  </div>
</div>
<style>
  <?= '#slider-'.$id_str ?> .trends-2021-slideshow__edge {
    background: linear-gradient(90deg, rgba(<?= $rgb ?>, 0.8) 0%, rgba(<?= $rgb ?>, 0) 82%);
  }
</style>

<!-- reset .container -->
<div class="trends-2021-container">