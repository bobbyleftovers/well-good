<?php

$is_branded = $post->recipe_card_field;
$fallback_img = $post->recipe_card_sub_field;

$thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
$download_link = get_field('download_link');
$dietary_terms = get_the_terms( $post->ID, 'dietary' );
$extras = get_field( 'extras' );
$instructions_video = get_field('video_id');

// Refer to plugin source in wp-ultimate-recipe/helpers/models/recipe.php for these functions
$recipe = new WPURP_Recipe( $post->ID );
$title = $recipe->title();
$description = $recipe->description();
$ingredients = $recipe->ingredients();
$instructions = $recipe->instructions();
$servings = $recipe->servings() ? $recipe->servings() : 1;
$servings_type = $recipe->servings_type();

$cook_time = $recipe->cook_time();
$cook_time_text = $recipe->cook_time_text();
$cook_time_all = "$cook_time $cook_time_text";

$prep_time = $recipe->prep_time();
$prep_time_text = $recipe->prep_time_text();
$prep_time_all = "$prep_time $prep_time_text";

$alt_image_base = $recipe->alternate_image_url('medium');
$alt_image_retina = $recipe->alternate_image_url('large');
$alt_image_alt = "Thumbnail for recipe";

$notes = $recipe->notes();
$hide_notes = get_field('hide_recipe_notes');

// Reference plugins/wp-ultimate-recipe/helpers/metadata.php
$meta = new WPURP_Metadata();
$metadata = $meta->get_metadata_array( $recipe );
$metadata = $meta->sanitize_metadata( $metadata );

?>

<section class="recipe-card">

	<header class="recipe-header">
		<div class="recipe-header__title-wrap">
			<h2 class="recipe-header__title"><?= $title ?></h2>

      <?php if( $download_link ) : ?>
        <a href="<?= $download_link ?>" title="Download Recipe" class="recipe-header__download-link">
          <span class="icon-download-arrow recipe-header__download"></span>
        </a>
			<?php endif; ?>

		</div>
		<p class="recipe-header__desc"><?= $description ?></p>
	</header>

	<div class="recipe-section recipe-section--details">
		<?php if( !$is_branded ) : ?>
			<div class="recipe-details__side">
				<?// TODO: Temp background-image solution, remove if/when image module debugged ?>
				<div class="recipe-details__image" style="background-image: url('<?= $alt_image_base ? $alt_image_base : $thumbnail_url; ?>')">
					<?php // the_module('image', $alt_image_base, $alt_image_retina, $alt_image_alt); ?>
				</div>
				<div class="recipe-details__rating">
					<?php the_module('rating', get_the_ID()); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="recipe-details__main">
			<ul class="recipe-details__list">
        <li class="recipe-details__list-item">
          <div class="recipe-detail__title"><?php if( !$is_branded ) : ?><span class="recipe-detail__icon icon-cutlery"></span> <?php endif; ?>Prep Time</div>
          <div class="recipe-detail__value"><?= $prep_time_all ?></div>
        </li>
        <li class="recipe-details__list-item">
          <div class="recipe-detail__title"><?php if( !$is_branded ) : ?><span class="recipe-detail__icon icon-cutlery"></span> <?php endif; ?>Cook Time</div>
          <div class="recipe-detail__value"><?= $cook_time_all ?></div>
        </li>
        <li class="recipe-details__list-item">
          <div class="recipe-detail__title"><?php if( !$is_branded ) : ?><span class="recipe-detail__icon icon-cutlery"></span> <?php endif; ?>Servings</div>
          <div class="recipe-detail__value"><?php if( !$is_branded ) : ?><input class="recipe-detail__input" id="js-servings-input" type="number" value="<?= $servings ?>" min="1"><?= $servings_type ?><?php else: ?><?= "$servings $servings_type" ?><?php endif; ?></div>
        </li>
        <?php if( $is_branded && $dietary_terms ) : ?>
					<li class="recipe-details__list-item">
						<div class="recipe-detail__title">Dietary</div>
							<div class="recipe-detail__value">
								<?php $i = 0; $count = count($dietary_terms);
									foreach ( $dietary_terms as $term ): ?>
                  <div class="recipe-dietary">
                    <?php
                    $i++;
                    $term_name = $term->name;
                    $term_icon_url = get_field('icon', $term);
                    ?>
                    <img class="recipe-dietary__icon recipe-after__term-image no-pin" src="<?= $term_icon_url ?>" alt="Icon for <?= $term_name ?>">
										<span class="recpe-dietary__name"><?php the_field('shortname', $term); ?></span>
                  </div>
                <?php endforeach; ?>
							</div>
					</li>
				<?php endif; ?>
			</ul>

			<h2 class="recipe-section__title">Ingredients</h2>
			<div data-module-init="recipe-card">
        <?php
        $previous_ing_group = 'initial';

        foreach ($ingredients as $key => $ing) :
					$amount = $ing['amount'];
					$unit = $ing['unit'];
          $ing_notes = isset($ing['notes']) ? $ing['notes'] : '';
          $ing_notes = substr($ing_notes, 0, 1) == ',' ? "$ing_notes" : " $ing_notes";
					$ingredient = $ing['ingredient'];
					$term = get_term_by('name', $ingredient, 'ingredient');
          $term_image = '';
          $term_sponsor_link = get_field('sponsored_link', $term);
          $ratio = (int)$amount / (int)$servings;
          $ingredient_tag = 'span';
          $is_sponsored =  get_field('sponsored_ingredient', $term);
          $external_link = False;

          if( !$is_branded ) {
            $external_link = True;
            if( $term_sponsor_link ) {
              $ingredient_tag = 'a';
            }
          } else if( $term_sponsor_link && ($is_branded && $is_sponsored)) {
            $ingredient_tag = 'a';
            $external_link = True;
          } ?>

          <?php if( $ing['group'] != $previous_ing_group ):
            if($key > 0): echo "</ul>"; endif;
            if($ing['group'] != ""): echo '<h3 class="recipe__group-title">' . $ing['group'] .'</h3>'; endif;
            echo '<ul class="recipe-ingredients">';
            $previous_ing_group = $ing['group'];
          endif; ?>

					<li class="recipe-ingredient<?= (!$is_branded) ? ' recipe-ingredient--standard' : ''; ?>">

            <?php if( $is_branded ):
              $term_image = get_field('image', $term) ? get_field('image', $term) : $fallback_img['url'];
            ?>

              <?php if( $external_link && $term_sponsor_link ): ?><a class="recipe-ingredient__link" data-vars-event="ingredients" data-vars-info="<?= $ingredient; ?>" href="<?= $term_sponsor_link ?>"><?php endif; ?>
                <div class="recipe-indredient__contents-wrap">
                  <div class="recipe-ingredient__image-wrap">
                    <?php the_module('image', $term_image, $term_image, $ingredient); ?>
                  </div>
                  <?php if( $is_sponsored ): ?><span class="recipe-ingredient__sponsor-notice">Sponsored</span><?php endif; ?>
                </div>
                <?php if( $external_link && $term_sponsor_link ): ?></a><?php endif; ?>
						<?php endif; ?>
            <<?= $ingredient_tag; echo ($external_link && $term_sponsor_link) ? " data-vars-event='ingredients' data-vars-info='$ingredient' href='$term_sponsor_link' target='_blank' rel='nofollow'" : '';?> class="recipe-ingredient__text<?= ($external_link && $term_sponsor_link) ? ' recipe-ingredient__text--sponsored' : ''; ?>"><span class="recipe-ingredient__calc js-ingredient-calc" data-ratio="<?= $ratio; ?>"><?= $amount ?></span> <?= "$unit $ingredient$ing_notes" ?><?php if( $external_link && $term_sponsor_link ): ?><span class="icon-external-link recipe-ingredient__icon-external"></span><?php endif; ?></<?= $ingredient_tag; ?>>
					</li>

          <?php $key == (count($ingredients) - 1) ? "</ul>" : ''; ?>

				<?php endforeach; ?>
      </div>

      <?php if( get_field('enable_chicory') ) : ?>
        <div class="recipe-ingredients__btn-wrap<?php if( $is_branded ): ?> recipe-ingredients__btn-wrap--sponsored<?php endif; ?>">
          <div class="chicory-order-ingredients"></div>
        </div>
      <?php endif; ?>

    </div>

	</div>

	<div class="recipe-section">
		<h2 class="recipe-section__title">Instructions</h2>
    <?php if( $is_branded && $instructions_video ) : ?>
      <div class="recipe-instructions__video">
        <?php echo do_shortcode('[bc_video video_id="'.$instructions_video.'" account_id="4872551774001" player_id="default"]') ?>
      </div>
    <?php endif; ?>

    <?php
    $previous_group = 'initial';

    foreach ($instructions as $key => $instruction) :
			$inst_image_alt = get_post_meta( $instruction['image'], '_wp_attachment_image_alt', true );
			$inst_image_base = wp_get_attachment_image_src($instruction['image'], 'medium')[0];
			$inst_image_retina = wp_get_attachment_image_src($instruction['image'], 'large')[0];
      ?>

      <?php if( $instruction['group'] != $previous_group ):
        if($key > 0): echo "</ol>"; endif;
        if($instruction['group'] != ""): echo '<h3 class="recipe__group-title">' . $instruction['group'] .'</h3>'; endif;
        echo '<ol class="recipe-instructions">';
        $previous_group = $instruction['group'];
      endif; ?>

			<li class="recipe-instruction__detail">
				<p class="recipe-instruction__desc"><?= $instruction['description'] ?></p>
				<?php if($instruction['image']): ?>
					<div class="recipe-instruction__image">
            <?php the_module('image', $inst_image_base, $inst_image_retina, $inst_image_alt); ?>
					</div>
				<?php endif; ?>
			</li>

      <?php $key == (count($instructions) - 1) ? "</ol>" : ''; ?>

		<?php endforeach; ?>

	</div>
  <?php if( !empty(trim($notes)) && !$hide_notes ) : ?>
    <div class="recipe-section">
      <h2 class="recipe-section__title recipe-section__title--notes">Recipe Notes</h2>
      <p><?= $notes ?></p>
    </div>
  <?php endif; ?>

</section>

<div class="recipe-after">
<?php if( !$is_branded ) : ?>
	<ul class="recipe-after__terms">
		<?php if( $dietary_terms ) : ?>
		<?php foreach ( $dietary_terms as $term ): ?>
			<?php
				$term_name = $term->name;
				$term_icon_url = get_field('icon', $term);
			?>
			<li class="recipe-after__term"><img class="recipe-after__term-image no-pin" src="<?= $term_icon_url ?>" alt="Icon for <?= $term_name ?>"> <?= $term_name ?></li>
		<?php endforeach; ?>
		<?php endif; ?>
	</ul>

	<div class="recipe-after__wysiwyg">
		<?php the_field('below_recipe_content'); ?>
	</div>
<?php else : if (have_rows('extras')) : ?>
  <div class="recipe-extras">
    <h3 class="recipe-section__title">Extras</h3>
    <ul class="recipe-extras__wrap">
    <?php while( have_rows ('extras') ) : the_row(); ?>
      <li class="recipe-extra">
        <a class="recipe-extra__link" href="<?php the_sub_field('link'); ?>">
          <?php if(get_sub_field('icon')) : ?>
            <img class="recipe-extra__icon no-pin" src="<?php the_sub_field('icon'); ?>" alt="Icon for <?= get_sub_field('text'); ?>">
          <?php endif; ?>
          <p class="recipe-extra__text"><?php the_sub_field('text'); ?></p>
        </a>
      </li>
    <?php endwhile; ?>
    </ul>
  </div>
<?php endif; endif; ?>
</div>

<?= '<script type="application/ld+json">' . json_encode( $metadata, JSON_UNESCAPED_SLASHES ) . '</script>'; ?>

