<?php
/**
 * Editorial Tag Page - Full Module
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */


$args = isset( $post->editorialtag_module_full_field ) ? $post->editorialtag_module_full_field : '';

$articles = $args['posts'];
$module_title = $args['title'];
$category_id = $args['category_id'];

$module_id = 'full background carousel';
?>

<?php 
if ( $articles ) : ?>
	<div class="container">
		<div class="editorialtag-module editorialtag-module-full" data-module-init="editorialtag-module-full"  data-module="<?= $module_id; ?>">
			<div class="editorialtag-module-full__slider">
				<div class="editorialtag-module-full__header">
					<div class="editorialtag-module-full__header--title"><?= $module_title; ?></div>
					<div class="editorialtag-module-full__header--arrows">
						<div class="editorialtag-module-full__header--arrow editorialtag-module-full__header--left"><?= get_svg('tag_page-prev'); ?></div>
						<div class="editorialtag-module-full__header--arrow editorialtag-module-full__header--right"><?= get_svg('tag_page-next'); ?></div>
					</div>
				</div>
				<div class="editorialtag-module-full__slides">
					<?php 
					foreach( $articles as $key => $article ) :  
						$article_id = $article->ID;
						$permalink = get_permalink( $article_id );
						$title = $article->post_title;
						$author_id = $article->post_author;
						$author_name = get_the_author_meta( 'display_name', $author_id );
						$author_url = get_author_posts_url( $author_id );
						$excerpt = $article->post_excerpt;
						$content = $article->post_content; ?>
						<div class="editorialtag-module-full__slide">
							<a href="<?= $permalink; ?>" data-vars-event="<?= "{$module_id} article"; ?>">
								<div class="editorialtag-module-full__slide--title"><?= $title; ?></div>
								<div class="editorialtag-module-full__slide--excerpt"><?= $excerpt; ?></div>
							</a>
							<div class="editorialtag-module-full__slide--author">By <a href="<?= $author_url ?>" data-vars-event="<?= "{$module_id} author"; ?>"><?= $author_name; ?></a></div>
						</div>
					<?php 
					endforeach; ?>
				</div>
			</div>
			<?php 
			foreach( $articles as $key => $article ) :  
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $article->ID ), 'article-retina' );
				$image_url = $image ? @$image[0] : ''; ?>
				<div class="editorialtag-module-full__background<?php if ( $key==0 ) { echo' editorialtag-module-full__background--active';}?>" style="background-image:url(<?= $image_url; ?>);"></div>
			<?php
			endforeach; ?>
		</div>
	</div>
<?php 
endif; ?>