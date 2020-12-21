<div
  class="shop-links__content"
  <?php if(!empty($hover_text_bg_color) && !empty($hover_text)): ?>
    style="background-color: <?= $hover_text_bg_color; ?>"
  <?php endif; ?>
	<?php if ( !is_feed() ): ?>
	  data-vars-event="shop link cta"
	  data-vars-info="<?= $title; ?>"
  <?php endif; ?>
>

  <?php if(!empty($hover_text)) : ?>
    <div class="shop-links__hover-text" data-vars-event="shop link description" data-vars-info="<?= $title; ?>"><?= $hover_text ?></div>
  <?php else: ?>
    <span <?= html_data_attr('data-vars-event', 'shop link cta') ?> <?= html_data_attr('data-vars-info', $title) ?> class="btn alt"<?php if( $button_color || $button_text_color ){

        $custom_style = '';
        $custom_style .= $button_color ? "background-color: $button_color;" : '';
        $custom_style .= $button_text_color ? " color: $button_text_color;" : '';

        echo "style='$custom_style'";
    }
    ?>>
      <?php echo $button_text; ?>
    </span>
  <?php endif; ?>

</div>
