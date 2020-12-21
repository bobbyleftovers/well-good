<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts in resepct to MSN's requireents.
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
<rss xmlns:dc="http://purl.org/dc/terms" xmlns:media="http://search.yahoo.com/mrss/" xmlns:mi="http://schemas.ingestion.microsoft.com/common/" version="2.0">
<channel>
    <title>Well+Good</title>
    <language>en-us</language>
    <link><?php bloginfo_rss('url') ?></link>
    <?php
      $args = array(
        'post_type' => 'post',
        'posts_per_page' => 50,
        'post_status' => 'publish',
        'tag__not_in' => array(
          6686, // 'video' tag
          6716 // 'branded' tag -- temporarily excluded for testing
        )
      );
      $query = new WP_Query( $args );

      while( $query->have_posts()) : $query->the_post();
      $exclude_from_feed = get_field('exclude_from_msn_feed');
      $published_date = get_the_date('c');
      $post_title = get_the_title_rss();
      $excerpt = get_the_excerpt() ? get_the_excerpt() : $post_title; // tempoary default solution --
      //this should be returning a portion of the post_content if excerpt is empty, but that doesn't seemt o be the case in testing

      $post_content = replace_figure(do_shortcode($post->post_content), $post->ID);

      if( !$exclude_from_feed ) :
    ?>
      <item>
        <title><?= $post_title; ?></title>
        <link><?php the_permalink_rss(); ?></link>
        <pubDate><?= $published_date; ?></pubDate>
        <guid isPermaLink="false"><?= $post->ID; ?></guid>
        <description><![CDATA[<?= $post_content; ?>]]></description>
        <dc:alternative><?= esc_attr(html_entity_decode($excerpt)); ?></dc:alternative>
        <dc:abstract><?= esc_attr(html_entity_decode($excerpt)); ?></dc:abstract>
        <dc:publisher>Well+Good</dc:publisher>
        <dc:creator><?php the_author(); ?></dc:creator>
        <dc:modified><?php the_modified_date( 'c'); ?></dc:modified>
        <mi:dateTimeWritten><?= $published_date; ?></mi:dateTimeWritten>
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
          // USE THE MEDIA TAG TO ADD IMAGES
          // Image params
          if( has_post_thumbnail() ) {
            $image_id = get_post_thumbnail_id();
            $image = get_post($image_id);
            $image_url = wp_get_attachment_image_src( $image_id, 'large' )[0];
            $image_alt = get_post_meta($image_id , '_wp_attachment_image_alt', true) ? get_post_meta($image_id , '_wp_attachment_image_alt', true) : $image->post_title;
            $image_credit = $image->post_excerpt ? $image->post_excerpt : 'Well+Good';
            $image_mime = $image->post_mime_type;
            $image_title = $image->post_title;
          } else {
            // Featured Image Fallback for any posts that don't have a featured image
            $fallback_image = wag_get_fallback_image();
            $image_id = $fallback_image['ID'];
            $image_url = $fallback_image['url'];
            $image_alt = $fallback_image['alt'] ? $fallback_image['alt'] : $fallback_image['title'];
            $image_credit = $fallback_image['caption'] ? $fallback_image['caption'] : 'Well+Good';
            $image_mime = $fallback_image['mime_type'];
            $image_title = $fallback_image['title'];
          }

          // Harcoding image syndication for now as per W+G's request.
          // However, should this change, and W+G not have image rights,
          // they'll need to provide information for licensorName and licensoryID
            $syndication = 1;
            $licensorName = null;
            $licensorID = null;
        ?>
          <media:content url="<?= $image_url; ?>">
            <media:thumbnail url="<?= esc_url($image_url); ?>" type="<?= $image_mime; ?>" />
            <media:credit><?= htmlspecialchars($image_credit); ?></media:credit>
            <media:title><?= htmlspecialchars($image_title); ?></media:title>
            <media:text><?= htmlspecialchars($image_alt); ?></media:text>
            <?php if( $syndication ) : ?>
              <mi:hasSyndicationRights><?= $syndication; ?></mi:hasSyndicationRights>
            <?php else : ?>
              <mi:licenseId><?= htmlspecialchars($licensorName); ?></mi:licenseId>
              <mi:licensorName><?= htmlspecialchars($licensorID); ?></mi:licensorName>
            <?php endif; ?>
          </media:content>
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
              $thumb_alt = get_post_meta($thumb_id , '_wp_attachment_thumb_alt', true) ? get_post_meta($thumb_id , '_wp_attachment_thumb_alt', true) : $post_title;
              $thumb_credit = $thumb->post_excerpt ? $thumb->post_excerpt : $article_title;
              $thumb_mime = $thumb->post_mime_type;
              $thumb_title = $thumb->post_title;
            endif;
        ?>
          <link rel="related" type="text/html" href="<?= esc_url($article_url); ?>" title="<?= esc_attr($article_title); ?>">
            <?php if( $thumb_id ): ?>
              <media:thumbnail url="<?= esc_url($thumb_url); ?>" type="<?= esc_attr($thumb_mime); ?>" />
              <media:credit><?= htmlspecialchars($thumb_credit); ?></media:credit>
              <media:title><?= htmlspecialchars($thumb_title); ?></media:title>
              <media:text><?= htmlspecialchars($thumb_alt); ?></media:text>
            <?php endif; ?>
          </link>
        <?php endforeach; endif; ?>
      </item>
    <?php endif; endwhile; ?>
  </channel>
</rss>
