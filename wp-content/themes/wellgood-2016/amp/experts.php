<?php
$post_id = get_the_ID();

$experts_tax = get_the_terms( $post_id, 'experts' );
$experts     = $experts_tax ? array_map(
	function ( $experts ) {
		$term_tax = $experts->taxonomy;
		$term_id  = $experts->term_id;
		$term     = get_term( $term_id, $term_tax );

		return array(
			'name'  	  => $term->name,
			'slug'			=> $term->slug,
			'title' 		=> get_field( 'expert_title', "{$term_tax}_{$term_id}" ),
			'bio'   		=> $term->description,
			'short_bio' => get_field( 'expert_short_bio', "{$term_tax}_{$term_id}" ),
			'url'   		=> get_field( 'expert_url', "{$term_tax}_{$term_id}" ),
			'image' 		=> get_field( 'expert_image', "{$term_tax}_{$term_id}" ),
		);
	},
	$experts_tax
) : array();

if ( ! empty( $experts ) ) : ?>
  <div class="amp-experts">
		<div class="amp-experts__header">
			Experts Referenced
		</div>
		<div class="amp-experts__grid">
			<?php foreach ( $experts as $expert ) :
				$link = get_permalink( get_page_by_path( 'experts' ) ) . "?expert={$expert['slug']}";
				?>
				<div class="amp-expert">
					<div class="amp-expert__name"><?= $expert['name']; ?></div>
          <div class="amp-expert__bio">
            <?= $expert['short_bio'] ?: $expert['title']; ?>
            <br />
            <a class="amp-expert__link" href="<?= $link; ?>">Learn More</a>
          </div>
				</div>
			<?php endforeach; ?>
		</div>
      </div>
<?php 
endif;