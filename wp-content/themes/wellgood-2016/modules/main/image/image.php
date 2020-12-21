<?php
global $post;

$is_amp = is_amp_context();
if (gettype($post->image_field) != 'array') :
  $image_src = isset($post->image_field) ? $post->image_field : '';
  $image_src_retina = isset($post->image_sub_field) ? $post->image_sub_field : '';
  $image_alt = isset($post->image_sub_sub_field) ? $post->image_sub_sub_field : '';
  $is_video = isset($post->image_sub_sub_sub_field) && $post->image_sub_sub_sub_field == 'has_video_tag' ? true : false;
  $no_pin_class = isset($post->image_sub_sub_sub_sub_field) ? true : false;
  $image_attrs = isset($post->image_sub_sub_sub_sub_sub_field) ? $post->image_sub_sub_sub_sub_sub_field : array();
  $image_width = '';
  $image_height = '';
  $class = '';
  $image_classes = '';
  $container_classes = '';
  $no_zoom_class = false;
  $franchise = NULL;
else :
  $args = $post->image_field;
  $class = array_key_exists('class', $args) ? $args['class'] : '';
  $image_src = array_key_exists('image_src', $args) ? $args['image_src'] : '';
  $image_src_retina = array_key_exists('image_src_retina', $args) ? $args['image_src_retina'] : '';
  $image_alt = array_key_exists('image_alt', $args) ? $args['image_alt'] : '';
  $is_video = array_key_exists('is_video', $args) && $args['is_video'] != false ? true : false;
  $no_pin_class = array_key_exists('no_pin_class', $args) && $args['no_pin_class'] ? true : false;
  $no_zoom_class = array_key_exists('no_zoom_class', $args) ? true : false;
  $franchise = array_key_exists('franchise', $args) ? $args['franchise'] : NULL;
  $image_attrs = array_key_exists('image_attrs', $args) ? $args['image_attrs'] : array();
  $image_width = array_key_exists('image_width', $args) ? $args['image_width'] : '';
  $image_height = array_key_exists('image_height', $args) ? $args['image_height'] : '';
  $image_classes = array_key_exists('image_classes', $args) ? $args['image_classes'] : '';
  $container_classes = array_key_exists('container_classes', $args) ? $args['container_classes'] : '';

  if ( $image_width || $image_height ) :
    $styles = array();

    if ( $image_height ) :
      $styles[] = 'height:' . $image_height . 'px';
    endif;
    if ( $image_width ) :
      $styles[] = 'width:' . $image_width . 'px';
    endif;
    $image_attrs[] = 'style="' . join(';', $styles) . '"';
  endif;
endif;

if(empty($image_src)):
	$image = wag_get_fallback_image();
	$image_src = $image ? $image['sizes']['medium'] : '';
	$image_src_retina = $image ? $image['sizes']['article-retina'] : '';
endif;

if (!empty($container_classes)) :
  if (gettype($container_classes) != 'array') :
    $container_classes = explode(' ', $container_classes);
  endif;
  array_push($container_classes, 'image-module');
else :
  $container_classes = array('image-module');
endif;
if ($no_zoom_class) :
  array_push($container_classes, 'no-zoom');
endif;

if (!empty($image_classes)) :
  if (gettype($image_classes) != 'array') :
    $image_classes = explode(' ', $image_classes);
  endif;
  array_push($image_classes, 'image-module-img');
  if ($no_pin_class) array_push($image_classes, 'no-pin');
  $image_classes = implode(' ', $image_classes);
else :
  $image_classes = 'image-module-img';
  if ($no_pin_class) $image_classes .= ' no-pin';
endif;
if(!empty($image_src)): ?>
  <div class="<?=$class?> <?= implode(' ', $container_classes); ?>" <?= is_string($image_attrs) ? $image_attrs : join(' ', $image_attrs); ?>>
    <?php if ($is_amp):?>
    <img src="<?= $image_src; ?>" alt="<?= esc_attr($image_alt); ?>" >
    <?php else: ?>
    <img
      src="<?= get_template_directory_uri() ?>/assets/img/spacer.gif"
      <?= html_data_attr("data-src", $image_src) ?>
      <?= is_string($image_attrs) ? $image_attrs : join(' ', $image_attrs); ?>
      class="<?= $image_classes ?>"
      <?php if(!empty($image_src_retina)): ?>
        data-src-retina="<?= esc_attr($image_src_retina); ?>"
      <?php endif; ?>
      alt="<?= esc_attr($image_alt); ?>" /><?php
      // @WORK FUTURE
      // We may add a franchise crest to the images
      // if ( $franchise ) :
      //   $franchise_image = $franchise['crest']['url'];
      //   $aspect = intval( $franchise['crest']['height'] ) / intval( $franchise['crest']['width'] );
      //   $franchise_width = 90;
      //   $franchise_height = $franchise_width * $aspect;

      //   echo '<div class="image-module__franchise" style="background-image:url(' . $franchise_image . ');height:' . $franchise_height . 'px;width:' . $franchise_width . 'px"></div>';
      // endif;
      if ( $is_video ) :
        echo '<div class="image-module__icon image-module__icon--play"></div>';
      endif;
    ?><noscript>
      <img src="<?= $image_src ?>" alt="<?= esc_attr($image_alt); ?>" />
    </noscript>
  <?php endif; ?>
  </div>
<?php
endif;
