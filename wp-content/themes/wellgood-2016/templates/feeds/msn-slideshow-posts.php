<?php
/**
 * RSS2 Feed Template for displaying RSS2 Slideshows in resepct to MSN's requirements.
 * This template is adapted from the wordpress default RSS2 template in wp-includes/feed-rss2.php
 *
 * @package WordPress
 */


global $post;

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$more = 1;


echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:media="http://search.yahoo.com/mrss/"
  xmlns:mi="http://schemas.ingestion.microsoft.com/common/"
  xmlns:dc="dc=http://purl.org/dc/elements/1.1/"
  xmlns:dcterms="http://purl.org/dc/terms/"
  xmlns:atom="http://www.w3.org/2005/Atom"

	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
  do_action( 'rss2_ns' );

	?>
<?php // > - required for closing the unused boilerplate code ?>
>

<channel>
  <title>Well+Good</title>
  <link><?php bloginfo_rss('url') ?></link>
  <description>Your Healthiest Relationship</description>

  <?php
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 50,
    'post_status' => 'publish',
    'tax_query' => array(
        array (
            'taxonomy' => 'backend_tag',
            'field' => 'slug',
            'terms' => 'slideshow',
        )
    ),
    'tag__not_in' => array(
      6686, // 'video' tag
      6716 // 'branded' tag
    )
  );
  $query = new WP_Query( $args );

  while( $query->have_posts() ) : $query->the_post();

    // Set up content
    $title = $post->post_title;
    $excerpt = $post->post_excerpt;
    $content = $post->post_content;
    $header_slideshow_id = get_field('slideshow', $post);
    $slideshow_ids = get_slideshow_ids($content);
   
    if( isset($header_slideshow_id) ) {
      array_push($slideshow_ids, $header_slideshow_id);
    }
    
    $slideshows = !empty($slideshow_ids) ? get_slideshows_from_ids($slideshow_ids) : null;
    // $default_sponsor = get_field('sponsor'); - holding in case we need to add it
    $author = get_the_author();
    $published_date = get_the_date('c');
    $modified_date = get_the_modified_date( 'c');

?>
  <item>
    <title><?= htmlspecialchars($title) ?></title>
    <link><?php the_permalink_rss(); ?></link>
    <guid isPermaLink="false"><?= $post->ID ?></guid>
    <description><?= htmlspecialchars($excerpt) ?></description>
    <dc:creator><?= htmlspecialchars($author) ?></dc:creator>

    <?php if( $slideshows ) :
      foreach($slideshows as $slideshow) :
        $slides = get_field('slides', $slideshow->ID);
        
        foreach($slides as $key=>$slide) :
          $image = $slide['image'];
          $image_base_url = $image['url'];
          $image_caption = $image['caption'];

          $cta = $slide['cta'];
          $cta_text = $cta['text'];
          $cta_link = $cta['link'];
          $cta_all = "<a href=\"$cta_link\">$cta_text</a>";
          // To remain hard coded until W+G does not have rights
          $syndication = 1;
          $licensorName = null;
          $licensorID = null;
        ?>
          <media:content url="<?= esc_url($image_base_url); ?>" type="image/jpeg">
            <media:thumbnail url="<?= esc_url($image_base_url); ?>" type="image/jpeg" />
            <media:credit><?= htmlspecialchars($image_caption) ?></media:credit>
            <media:title><?= htmlspecialchars($slide['title']) ?></media:title>
            <media:text>
              <?php if( $cta ) { ?>
                <![CDATA[<?= $cta_all ?><br />]]>
              <?php } ?>
              <![CDATA[<?= $slide['description'] ?>]]>
            </media:text>
            <?php if( $syndication ) : ?>
              <mi:hasSyndicationRights><?= $syndication; ?></mi:hasSyndicationRights>
            <?php else : ?>
              <mi:licenseId><?= htmlspecialchars($licensorName); ?></mi:licenseId>
              <mi:licensorName><?= htmlspecialchars($licensorID); ?></mi:licensorName>
            <?php endif; ?>
          </media:content>
        <?php endforeach; ?>
    <?php endforeach; endif; ?>

      <pubDate><?= $published_date ?></pubDate>
      <dcterms:modified><?= $modified_date ?></dcterms:modified>
      <media:keywords><?php
          $tags = get_the_tags();
          $tagCount = count($tags);
          if( !empty($tags) ) :
            foreach($tags as $key => $tag) :
              echo $tag->name . ($key < ($tagCount - 1) ? ', ' : '');
            endforeach;
          endif;
        ?></media:keywords>
      <?php
      // RELATED LINKS
      $related_posts = get_related_posts($post->ID, 12, 3);

      if( !empty($related_posts) ) :
        foreach($related_posts as $key => $related_post) :
          $article_url = get_the_permalink( $related_post->ID );
          $article_title = $related_post->post_title;
          $thumb_id = get_post_thumbnail_id($related_post->ID);

          if( $thumb_id ) :
            $thumb = get_post( $thumb_id ) ? get_post( $thumb_id ) : false;
            $thumb_url = wp_get_attachment_image_src( $thumb_id, 'large' )[0];
            $thumb_alt = get_post_meta($thumb_id , '_wp_attachment_thumb_alt', true) ? get_post_meta($thumb_id , '_wp_attachment_thumb_alt', true) : $article_title;
            $thumb_credit = $thumb->post_excerpt ? $thumb->post_excerpt : $article_title;
            $thumb_mime = $thumb->post_mime_type;
            $thumb_title = $thumb->post_title;
          endif;
      ?>
        <atom:link rel="related" type="text/html" href="<?= esc_url($article_url); ?>" title="<?= esc_attr($article_title); ?>">
          <?php if( $thumb_id ): ?>
            <media:thumbnail url="<?= esc_url($thumb_url); ?>" type="<?= esc_attr($thumb_mime); ?>" />
            <media:credit><?= htmlspecialchars($thumb_credit); ?></media:credit>
            <media:title><?= htmlspecialchars($thumb_title); ?></media:title>
            <media:text><?= htmlspecialchars($thumb_alt); ?></media:text>
          <?php endif; ?>
        </atom:link>
      <?php endforeach; endif; ?>
    </item>
  <?php endwhile;?>
  </channel>
</rss>
