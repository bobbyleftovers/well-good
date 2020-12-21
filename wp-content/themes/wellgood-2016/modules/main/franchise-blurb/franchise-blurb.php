<?php
/**
 * Franchise Blurb
 *
 * If an editorial tag has the "Franchise" option
 * turned on, it will display under a post's hero image
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset( $post->franchise_blurb_field ) ? $post->franchise_blurb_field : '';

$type = array_key_exists( 'type', $args ) && $args['type'] === 'branded' ? 'branded' : 'editorial';
if ( $type === 'editorial' ) :
  $franchise = array_key_exists( 'franchise', $args ) ? $args['franchise'] : '';
  $franchise_obj = get_category( $franchise );
  $franchise_url = get_category_link( $franchise );
  $franchise_title = $franchise_obj->name;
  $overrides = array_key_exists( 'overrides', $args ) ? $args['overrides'] : array();
  $taxonomy = 'category';

  $sponsor_text = '';
  if ( get_field( 'editorialtag_sponsored', "{$taxonomy}_{$franchise}" ) == true || ( isset( $overrides['sponsor'] ) && $overrides['sponsor'] ) ) :
    $sponsor_text = ( isset( $overrides['sponsor'] ) && $overrides['sponsor'] ) ? $overrides['sponsor'] : get_field( 'editorialtag_sponsor', "{$taxonomy}_{$franchise}", false );
  endif;

  $description = isset( $overrides['description'] ) && $overrides['description'] ? $overrides['description'] : get_field( 'editorialtag_franchise_description', $taxonomy . '_' . $franchise );
  $logo = isset( $overrides['logo'] ) && $overrides['logo'] ? $overrides['logo'] : get_field( 'editorialtag_franchise_logo', $taxonomy . '_' . $franchise );
  $more_link = isset( $overrides['more_link'] ) && $overrides['more_link'] ? $overrides['more_link'] : get_field( 'editorialtag_franchise_see_more_link', $taxonomy . '_' . $franchise );

elseif ( $type === 'branded' ) :
  $overrides = array_key_exists( 'overrides', $args ) ? $args['overrides'] : array();
  $sponsor_text = '';
  if ( isset( $overrides['sponsor'] ) && $overrides['sponsor'] ) :
    $sponsor_text = ( isset( $overrides['sponsor'] ) && $overrides['sponsor'] ) ? $overrides['sponsor'] : '';
  endif;

  $description = isset( $overrides['description'] ) && $overrides['description'] ? $overrides['description'] : '';
  $logo = isset( $overrides['logo'] ) && $overrides['logo'] ? $overrides['logo'] : '';
  $more_link = isset( $overrides['more_link'] ) && $overrides['more_link'] ? $overrides['more_link'] : '';

endif;

$is_amp = is_amp_endpoint();
?>


<?php if ( $description && $logo ) : 
  $image_aspect = intval( $logo['height'] ) / intval( $logo['width'] );
  $image_src = $logo['sizes']['medium'];

  $logo_width = 100;
  $logo_height = $logo_width * $image_aspect;
  $image_args = array(
    'image_src' => $image_src,
    'image_width' => $logo_width,
    'image_height' => $logo_height,
    'no_pin_class' => true,
    'container_classes' => 'franchise-blurb__image-module'
  );

  $mobile_logo_width = 90;
  $mobile_logo_height = $mobile_logo_width * $image_aspect;
  $mobile_image_args = array(
    'image_src' => $image_src,
    'image_width' => $mobile_logo_width,
    'image_height' => $mobile_logo_height,
    'no_pin_class' => true,
    'container_classes' => array(
      'franchise-blurb__image-module',
      'franchise-blurb__image-module--mobile'
    )
  );
  ?>

  <?php if ( $is_amp ) : ?>
    <div class="franchise-blurb__sponsor">
      <div><?= $sponsor_text; ?></div>
    </div>
  <?php endif; ?>
  <div class="franchise-blurb outlines-corners relative p-e20 mt-e30 sm:flex sm:items-start sm:flex-row" style="min-height:<?= $mobile_logo_height + 70; ?>px">
    <?php if ( $sponsor_text && ! $is_amp ) : ?>
      <div class="franchise-blurb__sponsor franchise-blurb__sponsor--mobile mb-e10 flex items-start sm:hidden">
        <div class="franchise-blurb__sponsor--text text-tag text-gray font-bold m-0 text-gray text-center inline-block"><?= $sponsor_text; ?></div>
      </div>
    <?php endif; ?>
    <div class="franchise-blurb__franchise">
      <div class="franchise-blurb__logo">
        <?php if ($is_amp) : ?>
          <img src="<?= $image_src; ?>" />
        <?php else :
          the_module( 'image', $image_args );
          the_module( 'image', $mobile_image_args );
        endif; ?>
      </div>
      <?php if ( $sponsor_text && ! $is_amp ) : ?>
        <div class="franchise-blurb__sponsor mt-e10 items-start justify-center hidden sm:flex w-e100">
          <div class="franchise-blurb__sponsor--text text-tag text-gray font-bold m-0 text-gray text-center inline-block"><?= $sponsor_text; ?></div>
        </div>
      <?php endif; ?>
    </div>

    <?php if ( $description ) : ?>
      <div class="franchise-blurb__description text-default mt-e10 text-center inline sm:inline-block sm:mt-0 sm:text-left">
        <span class="italic text-gray"><?php echo $description; ?></span><?php
        if ( $more_link && isset( $more_link['url'] ) ) :
          $link_url = $more_link['url'];
          $link_title = isset($more_link['title']) ? $more_link['title'] : $link_url;
          $link_target = isset($more_link['target']) ? $more_link['target'] : '_self';
          ?><span> <a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>"><?php echo $link_title; ?></a></span>
        <?php elseif ( $type === 'editorial' ) :
          $link_url = $franchise_url;
          $link_title = "Read more stories from {$franchise_title}";
          $link_target = '_self'; 
          ?><span> <a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>"><?php echo $link_title; ?></a></span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>
