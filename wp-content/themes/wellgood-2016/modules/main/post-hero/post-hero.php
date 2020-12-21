<?php
/**
 * Post hero
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


global $post;
$args = $post->post_hero_field;

$default_template = 'legacy';

$post_id = array_key_exists( 'post_id', $args ) ? $args['post_id'] : '';
$is_branded = array_key_exists( 'is_branded', $args ) && $args['is_branded'] === true ? true : false;
$partners = array_key_exists( 'partners', $args ) ? $args['partners'] : array();
$show_featured_image = get_field( 'show_featured_image' ) == 'yes' ? true : false;

$hero_data = get_post_meta( $post_id, '_post_hero_data', true ) ?: array();

$is_legacy = is_post_legacy( $post_id );
$default_template =  $is_legacy ? 'legacy' : 'image';
$template_name = array_key_exists( 'template_name', $hero_data ) && $hero_data['template_name'] && $hero_data['template_name'] !== 'standard' ? $hero_data['template_name'] : $default_template;

if ( ( array_key_exists( 'template_name', $hero_data ) && $hero_data['template_name'] && $hero_data['template_name'] !== 'standard' ) && ! ( $hero_data['template_name'] === 'legacy' && ! $is_legacy ) ) :
  $template_name = $hero_data['template_name'];
elseif ( ! is_post_legacy( $post_id ) ) :
  $template_name = 'image';
else :
  $template_name = $default_template;
endif;

// Post data
$hero_tag = get_field( 'hero_tag', $post_id );
$hero_obj = isset( $hero_tag ) && $hero_tag ? get_term( $hero_tag, 'category' ) : null;
$post_title = wp_strip_all_tags( get_the_title( $post_id ) );
$post_date = get_field( '_postextra_hide_date', $post_id ) != 'yes' ? get_the_time( 'F j, Y', $post_id ) : '';

// Featured image
$show_featured_image = get_field( 'show_featured_image', $post_id );

// Author data
$post_author_id = get_post_field( 'post_author', $post_id );
$post_author_avatar = get_avatar( $post_author_id, 32 );
$post_show_author_link = get_field( 'show_author', "user_{$post_author_id}" );
$post_author_link = $post_author_id ? esc_url( get_author_posts_url( $post_author_id ) ) : false;
$post_author_name = get_the_author_meta( 'display_name', $post_author_id );

// Markup
$classes = array(
  'header_container' => array( 
    'post-hero' 
  ),
  'hero_breadcrumbs' => array(
    'post-hero__breadcrumbs',
    'text-label',
    'w-full',
    'mt-e20',
    'mb-e18',
    'inline-block'
  ),
  'hero_info' => array(
    'post-hero__info',
    'flex',
    'flex-col',
    'mt-0',
    'mx-auto'
  )
);

$classes['header_container'][] = "post-hero--{$template_name}-template";

if ( $hero_obj ) :
  $hero_link = get_category_link( $hero_obj->term_id );
  $hero_name = $hero_obj->name;

  $classes['header_container'][] = "post-hero--with-breadcrumbs";
endif;

if ( $template_name !== 'legacy' ) :
  $classes['hero_breadcrumbs'][] = 'text-center';
  $classes['hero_info'][] = 'text-center';
endif; 

$author_markup = array();
if ( $post_author_avatar ) :
  $author_markup[] = '<p class="avatar-wrapper mt-0">';

  if ( $post_show_author_link ) :
    $author_markup[] = "<a href='{$post_author_link}'>{$post_author_avatar}</a>";
  else :
    $author_markup[] = $post_author_avatar;
  endif;

  $author_markup[] = '</p>';
endif;

$author_markup[] = '<p class="text-byline mt-0 ml-e12 text-gray-dark">';

if ( $post_show_author_link ) :
  $author_markup[] = "<a href='{$post_author_link}'>{$post_author_name}</a>";
else :
  $author_markup[] = "<span>{$post_author_name}</span>";
endif;

if ( $post_date ) :
  $author_markup[] =  "・<span class='text-byline text-gray-dark'>{$post_date}</span>";
endif;

$author_markup[] = '</p>';

$container_data = array();
if ( $template_name === 'video' ) :
  $container_data[] = 'data-module-init="post-hero"';
endif;
?>

<?php if ( $is_branded && ! empty( $partners ) ) :
  $partner = $partners[0];

  $sponsorship_text = get_field( 'partnership_text', 'options' ) ?: 'Paid Content';
  $partner_logo = get_field( 'partner_logo', "partners_{$partner->term_id}" );
  $partner_url = get_field( 'partner_mention_url', $post_id ) 
    ?? get_field( 'partner_url', "partners_{$partner->term_id}" );

  if (!get_field('partner_show_logo', "partners_{$partner->term_id}")) {
    $partner_logo = NULL;
  }

  $classes['sponsor_banner'] = array( 'post-hero__sponsor-banner' );
  $classes['sponsor_banner'][] = "post-hero__sponsor-banner--{$template_name}-template";
  if ( ( $template_name === 'image' && ( array_key_exists( 'hero_image_size', $hero_data ) && $hero_data['hero_image_size'] ) ) || $template_name === 'video' ) :
    $classes['sponsor_banner'][] = "post-hero__sponsor-banner--large";
  endif; ?>
  <div class="<?= implode( ' ', $classes['sponsor_banner'] ); ?>" data-module-init="sponcered-header">
    <div class="container">
      <span><?= $sponsorship_text; ?></span>
      <?php if ( $partner_logo ) : ?>
        <a class="post-hero__sponsor-logo" <?php if ($partner_url) echo "href='$partner_url' target='_blank'"; ?>>
          <?php the_module( 'image', array(
            'image_src' => $partner_logo['sizes']['medium'],
          ) ); ?>
        </a>
      <?php else : 
        $sponsor_attrs = array(
          'class' => array( 'post-hero__sponsor-name' )
        );
        if ( $partner_url ) :
          $sponsor_attrs['class'][] = 'hover-underline-gray';
          $sponsor_attrs['href'] = $partner_url;
          $sponsor_attrs['target'] = '_blank';
        endif;
        ?>
        <a <?= compile_attrs( $sponsor_attrs ); ?>>
          <?= ctype_lower( $partner->name ) ? verify_title_case( $partner->name ) : $partner->name; ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
<div class="<?= implode( ' ', $classes['header_container'] ); ?>"<?= implode( ' ', $container_data ); ?>>
  <header class="post-hero__header container">

    <?php if ( $hero_obj ) : ?>
      <span class="<?= implode( ' ', $classes['hero_breadcrumbs'] ) ?>">
        <a class="inline hover-underline-seafoam-dark text-seafoam-dark" href="<?= $hero_link; ?>">
          <span class="text-label">
            <?= $hero_name; ?>
          </span>
        </a>
      </span>
    <?php endif; ?>

    <div class="<?= implode( ' ', $classes['hero_info'] ); ?>">
      <h1 class="text-h1--article mt-0 mb-e25">
        <?= $post_title ?>
      </h1>

      <div class="post-hero__author flex items-center justify-center">
        <?= implode( $author_markup ); ?>
      </div>
    </div>

  </header>

  <?php
  switch ( $template_name ) :
    case 'video' :
      $video_data = array_key_exists( 'video', $hero_data ) ? $hero_data['video'] : array();
      $video_player = array_key_exists( 'player', $video_data ) ? $video_data['player'] : '';
      $video_id = array_key_exists( 'id', $video_data ) ? $video_data['id'] : '';
      $video_thumbnail = array_key_exists( 'thumbnail', $video_data ) ? $video_data['thumbnail'] : '';
      ?>

      <?php switch ( $video_player ) :
        case 'youtube' : ?>
          <div class="container post-hero__video-container mt-e30">
            <div class="post-hero__video">
              <span class="post-hero__video-play-button"></span>
              <div class="post-hero__video-cover" style="background-image: url('<?= $video_thumbnail; ?>');"></div>
              <iframe src='<?= "//www.youtube.com/embed/{$video_id}?modestbranding=1&rel=0"; ?>' frameborder="0" allowfullscreen allow="autoplay" class="post-hero__iframe"></iframe>
            </div>
          </div>
          <?php break;

        case 'jwplayer' :
          $player_id = array_key_exists( 'player_id', $video_data ) ? $video_data['player_id'] : '';
          $meta_entries = array_filter( array(
            array_key_exists( 'upload_date', $video_data ) ? '<meta itemprop="uploadDate" content="' . $video_data['upload_date'] . '"/>' : '',
            array_key_exists( 'name', $video_data ) ? '<meta itemprop="name" content="' . $video_data['name'] . '"/>' : '',
            array_key_exists( 'duration', $video_data ) ? '<meta itemprop="duration" content="' . $video_data['duration'] . '" />' : '',
            array_key_exists( 'thumbnail_url', $video_data ) ? '<meta itemprop="thumbnailUrl" content="' . $video_data['thumbnail_url'] . '"/>' : '',
            array_key_exists( 'content_url', $video_data ) ? '<meta itemprop="contentUrl" content="' . $video_data['content_url'] . '"/>' : ''
          ), function( $meta_entry ) {
            return $meta_entry != '';
          } );
          ?>

          <div itemscope itemtype="https://schema.org/VideoObject" class="container post-hero__video-container mt-e30">
            <?= implode( '', $meta_entries ); ?>
            <div style="position:relative; overflow:hidden; padding-bottom:56.25%">
              <iframe src="<?= "https://cdn.jwplayer.com/players/$video_id-$player_id.html"; ?>" width="100%" height="100%" frameborder="0" scrolling="auto" title="1" style="position:absolute;" allowfullscreen></iframe>
            </div>
          </div>
          <?php break;

      endswitch;
      break;

    case 'image' :
      $hero_image_size = array_key_exists( 'hero_image_size', $hero_data ) ? $hero_data['hero_image_size'] : 'small';
      $hero_image = array_key_exists( 'image', $hero_data ) ? $hero_data['image'] : array(
        'image_src' => get_the_post_thumbnail_url( $post_id, 'large'),
        'image_src_retina' => get_the_post_thumbnail_url( $post_id, 'large'),
        'image_alt' => "Thumbnail for {$post_title}"
      );
      $caption = array_key_exists( 'caption', $hero_data ) ? $hero_data['caption'] : '';
      $pinterest_link = '//pinterest.com/pin/create/link/?url=' . urlencode(get_the_permalink( $post_id )) . '&amp;description=' . wg_esc_url( $post_title ) . '&amp;media=' . urlencode( $hero_image['image_src'] );
      ?>

      <div class='<?php echo "container post-hero__image-container post-hero__image-container--{$hero_image_size} mt-e30"; ?>'>
        <div class="relative">
          <?php the_module( 'image', array(
            'image_src' => $hero_image['image_src'],
            'image_src_retina' => $hero_image['image_src_retina'],
            'image_alt' => $hero_image['image_alt']
          ) ); ?>
          <a target="_blank" href="<?= $pinterest_link; ?>" class="post__pin-link block">
            <span class="post__pin-wrapper">
              <span class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest">
                <span class="icon-pinterest-p"></span>
              </span>
            <span class="post__pin-label">Pin It</span>
            </span>
          </a>
        </div>
      </div>

      <?php if ( $caption ) : ?>
        <figcaption class="container text-caption not-italic text-center mt-e8"><?= $caption; ?></figcaption>
      <?php endif; ?>

      <?php break; ?>

  <?php endswitch; ?>
</div>
