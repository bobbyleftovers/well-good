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

$videos = get_brightcove_videos('24 hours');

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
<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xmlns:mi="http://schemas.ingestion.microsoft.com/common/" version="2.0">
<channel>
    <title>Well+Good</title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description>Your Healthiest Relationship</description>
    <?php
    foreach ($videos as $video) :

      $pub_date = $video->published_at;
      $modified_date = $video->updated_at;

      $video_desc = $video->long_description ? $video->long_description : $video->description;
      $video_title = $video->name;
      $video_author = 'Well+Good, LLC';
      $video_duration = $video->duration;
      $video_data = $video->sources[4];
      $video_type = isset($video_data->container) ? 'video/' . $video_data->container : $video_data->type;

      $video_url = $video_data->src;
      $video_thumb_url = $video->poster;
      list($video_thumb_width, $video_thumb_height, $video_thumb_type, $video_thumb_attr) = getimagesize($video_thumb_url);
      $video_caption = $video->description;
      $video_id = $video->id;
      $keywords = $video->tags;
    ?>
      <item>
        <guid isPermaLink="false"><?= $video_id; // $post->ID; ?></guid>
        <title><?= htmlspecialchars($video_title); // $post->title; ?></title>
        <description><?= wpautop(htmlspecialchars($video_desc)); ?></description>
        <author><?= $video_author; ?></author>
        <media:content duration="<?= $video_duration; ?>" type="<?= $video_type; ?>" url="<?= esc_url( $video_url ); ?>">
          <media:thumbnail url="<?= esc_url($video_thumb_url); ?>" type="<?= image_type_to_mime_type($video_thumb_type); ?>" height="<?= $video_thumb_height; ?>" width="<?= $video_thumb_width; ?>" />
          <media:title><?= htmlspecialchars($video_caption); ?></media:title>
          <media:text><?= htmlspecialchars($video_desc); // how is this different from the description tag above?  ?></media:text>
          <media:copyright><?= htmlspecialchars($video_author); ?></media:copyright>
        </media:content>
        <pubDate><?= $pub_date; ?></pubDate>
        <lastBuildDate><?= $modified_date; // the_modified_date( 'c' ); ?></lastBuildDate>
        <media:keywords><?php
          foreach($keywords as $key=>$keyword){
            echo htmlspecialchars( $keyword ) . ($key < (count($keywords) - 1) ? ', ' : '');
          }
        ?></media:keywords>
      </item>
    <?php endforeach; ?>
  </channel>
</rss>
