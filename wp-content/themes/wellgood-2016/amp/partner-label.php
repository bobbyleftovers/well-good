<?php

  $partners = get_the_terms( get_the_ID(), 'partners');
  $is_branded = article_is_branded(get_the_ID());
  if (!empty($partners) && $is_branded):
    $post_id = get_the_ID();
    $template_name = get_field( 'post_hero_type', $post_id );

    $partner = $partners[0];

    $sponsorship_text = get_field( 'partnership_text', 'options' ) ?: 'Paid Content:';
    $partner_logo = get_field( 'partner_logo', "partners_{$partner->term_id}" );
    $partner_url = get_field( 'partner_url', "partners_{$partner->term_id}" );

    if (!get_field('partner_show_logo', "partners_{$partner->term_id}")) {
      $partner_logo = NULL;
    }

    $sponsor_banner_container_classes = array( 'post-hero__sponsor-banner', 'amp-sponcered' );
    $sponsor_banner_container_classes[] = "post-hero__sponsor-banner--{$template_name}-template";
?>
    <div class="<?= implode( ' ', $sponsor_banner_container_classes ); ?>">
      <span><?= $sponsorship_text; ?></span>
      <?php if ( $partner_logo ) :
        $ratio = $partner_logo['width'] / $partner_logo['height'];
        $height = 20;
        $width = $ratio * $height;
      ?>
        <a class="amp-sponcered__logo post-hero__sponsor-logo" <?php if ($partner_url) echo "href='$partner_url' target='_blank'"; ?>>
          <img src="<?= $partner_logo['url']; ?>" height="<?= $height ?>" width="<?= $width ?>" alt="">
        </a>
      <?php else : ?>
        <a class="post-hero__sponsor-name" <?php if ($partner_url) echo "href='$partner_url' target='_blank'"; ?>>
          <?= ctype_lower( $partner->name ) ? verify_title_case( $partner->name ) : $partner->name; ?>
        </a>
      <?php endif; ?>
    </div>
<?php endif; ?>
