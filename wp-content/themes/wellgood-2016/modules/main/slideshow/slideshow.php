
<?php
  global $post;
  $id = $post->slideshow_field;
  $slideshow_url = get_the_permalink() . 'slide/' . $id;
  $slides = get_field('slides', $id);
  $default_sponsor = get_field('sponsor', $id);
  $first_slide_data = [];
  $slides_total = count($slides);
  $slide_data_vars = (!is_feed()) ? ' data-slideshow-url="'.$slideshow_url.'" data-module-init="flickity slideshow" data-arrow="long" ' : '';
  $amp_caption_size = 0; // 24% height
  $amp_height = 1+$amp_caption_size/1*(1+$amp_caption_size);
?>
<amp-state id="slideShow">
  <script type="application/json">
    {
      "activeSlideIndex": 0
    }
  </script>
</amp-state>
<?php echo is_amp_endpoint() ? '<amp-carousel on="slideChange:AMP.setState({ slideShow: { activeSlideIndex: event.index } })" class="slideshow-amp" width="'.$slides[0]['image']['sizes']['medium-width'].'" height="'.$slides[0]['image']['sizes']['medium-height'].'" layout="responsive" type="slides" controls>' : '<div class="slider desktop-enabled" ' . $slide_data_vars . '>' ?>
  <?php foreach($slides as $key=>$slide) :

    $image = $slide['image'];

    $image_alt = $image['alt'];
    $image_base_url = $image['url'];
    $image_retina = $image['url']; // TODO replace with retina size
    $image_amp = array(
      'url' => $image['sizes']['medium'],
      'width' => $image['sizes']['medium-width'],
      'height' => $image['sizes']['medium-height']
    );

    $cta = $slide['cta'];
    $cta_text = $cta['text'];
    $cta_link = $cta['link'];

    $current_slide_index = $key + 1;
    $sponsor_override = $slide['sponsor'];
    $sponsor = !empty($sponsor_override['name']) ? $sponsor_override : $default_sponsor;
    $sponsor_name = $sponsor['name'];
    $sponsor_logo_url = $sponsor['logo'];
    $sponsor_link = $sponsor['link'];
    $sponsor_label = $sponsor['label'];

    $slide_data = array(
      'count' => $current_slide_index,
      'title' => $slide['title'],
      'description' => $slide['description'],
      'cta_text' => $cta['text'],
      'cta_link' => $cta['link'],
      'sponsor' => array(
        'name' => $sponsor['name'],
        'logo_url' => $sponsor['logo'],
        'link' => $sponsor['link'],
        'label' => $sponsor['label']
      )
    );

    if ( $current_slide_index == 1 ) :
      $first_slide_data = $slide_data;
    endif;

    $slide_json = htmlspecialchars(json_encode($slide_data), ENT_QUOTES, 'UTF-8');

    ?>

    <?php if( !is_amp_endpoint() ) : ?>
      <div class="slider__slide" data-slide-data="<?php echo $slide_json ?>">
        <div class="slide__image-wrap" aria-hidden="true">
          <?php the_module('image', $image_base_url, $image_retina, $image_alt, '', 'no-pin') ?>
        </div>
      </div>
    <?php else: // Is AMP ?>
      <figure class="slide">
        <amp-img layout="responsive" src="<?php echo  $image_amp['url'] ?>" alt="<?php echo $image_alt; ?>" width="<?php echo $image_amp['width'] ?>" height="<?php echo $image_amp['height'] ?>"></amp-img>
      </figure>
    <?php endif; ?>

  <?php endforeach; ?>
<?php echo is_amp_endpoint() ? '</amp-carousel>' : '</div>' ?>
<?php if (is_amp_endpoint()): ?>
  <?php
    $slideIndex = 0;
    foreach($slides as $key=>$slide):
  ?>
    <figcaption <?= $slideIndex === 0 ? '' : 'hidden';?> data-amp-bind-hidden="slideShow.activeSlideIndex != <?= $slideIndex;?>" class="slideshow-amp__caption">
      <div class="slideshow-amp__caption__content">
      <?php if (isset($slide['title'])) : ?>
        <span class="slideshow-amp__caption__title"><?php echo $slide['title'] ?></span>
      <?php endif; ?>
      <?php if (isset($slide['description'])) : ?>
        <span class="slideshow-amp__caption__description"><?php echo $slide['description']; ?></span>
      <?php endif; ?>
      <?php if ( $cta_text || $sponsor_name ): ?>
        <?php if( $cta_text ): ?>
          <strong class="slideshow-amp__caption__cta"><a href="<?php echo $cta_link ?>"><?php echo $cta_text ?></a>.</strong>
        <?php endif; ?>
        <?php if( $sponsor_name ): ?>
            <p class="slideshow-amp__caption__sponsor"><a href="<?php echo $sponsor_link ?>"><?php echo $sponsor_label ?> <?php echo $sponsor_name ?></a>.</p>
        <?php endif; ?>
      <?php endif; ?>
      </div>
    </figcaption>
  <?php $slideIndex++; endforeach; ?>
<?php endif;?>

<?php if( !is_amp_endpoint() ): ?>
  <div class="slider__meta js-slide-data-container" aria-hidden="true">
    <p class="slide__count"><span <?php echo html_data_attr("data-slide-meta", "count") ?> class="slide__count__current">1</span>/<?php echo $slides_total ?></p>
    <h3 class="slide__title" <?php echo html_data_attr("data-slide-meta", "title") ?>><?php echo $first_slide_data['title'] ?></h3>
    <div class="slide__description" <?php echo html_data_attr("data-slide-meta", "description") ?>><?php echo $first_slide_data['description'] ?></div>
    <div class="slide__footer">
      <div class="slide__cta <?php echo empty($first_slide_data['cta_text']) ? 'js-is-hidden' : '' ?>">
        <a class="slide__cta__btn" <?php echo html_data_attr("data-slide-meta", "cta_btn") ?> href="<?php echo $first_slide_data['cta_link'] ?>"><?php echo $first_slide_data['cta_text'] ?></a>
      </div>
      <?php if (!empty($first_slide_data['sponsor']['logo_url']) && !empty($first_slide_data['sponsor']['name'])) : ?>
        <div class="slide__sponsor <?php echo empty($first_slide_data['sponsor']['name']) ? 'js-is-hidden' : '' ?>">
          <p class="slide__sponsor__label" <?php echo html_data_attr("data-slide-meta", "sponsor_text") ?>>
            <?php echo $first_slide_data['sponsor']['label'] ?> <?php echo $first_slide_data['sponsor']['name'] ?>
          </p>
          <a class="slide__sponsor__link" <?php echo html_data_attr("data-slide-meta", "sponsor.link") ?> href="<?php echo $first_slide_data['sponsor']['link'] ?>"><img class="slide__sponsor__logo no-pin" <?php echo html_data_attr("data-slide-meta", "sponsor.logo_url") ?> src="<?php echo $first_slide_data['sponsor']['logo_url'] ?>" alt="Logo for <?php echo $first_slide_data['sponsor']['name'] ?>"></a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
