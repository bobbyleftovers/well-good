<?php
/**
 * SmartFormat Feed Template for displaying SmartFormat Posts feed.
 *
 * @package SmartFormat
 */

header( 'Content-Type: ' . feed_content_type( 'smartformat' ) . '; charset=' . get_option( 'blog_charset' ), true );

echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'smartformat' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:media="http://search.yahoo.com/mrss/"
	xmlns:snf="http://www.smartnews.be/snf"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'smartformat_ns' );
	?>
>

<channel>
	<title><?php bloginfo_rss('name'); ?></title>
	<link><?php bloginfo_rss( 'url' ); ?></link>
	<description><?php bloginfo_rss( 'description' ); ?></description>
	<lastBuildDate><?php
		$date = get_lastpostmodified( 'GMT' );
		echo $date ? mysql2date( 'r', $date, false ) : date( 'r' );
	?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<ttl><?php the_smartformat_ttl(); ?></ttl>
	<snf:logo><url><?php echo wp_get_attachment_url(get_option('smartformat_logo_attachment_id')) ?></url></snf:logo>
	<?php
	/**
	 * Fires at the end of the SmartFormat Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'smartformat_head' );

	while ( have_posts() ) :
		the_post();
	?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php the_permalink_rss(); ?></link>
		<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
		<dc:creator><![CDATA[<?php the_author(); ?>]]></dc:creator>
		<?php the_category_rss( 'rss2' ); ?>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<?php if ( has_post_thumbnail() ) : ?>
		<media:thumbnail><?php the_post_thumbnail_url('full'); ?></media:thumbnail>
		<?php endif; ?>
		<media:status>active</media:status>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
		<content:encoded><![CDATA[<?php echo get_the_content_feed( 'smartformat' ); ?>]]></content:encoded>

		<?php if ( have_smartformat_sponsored_links() || has_smartformat_adcontent() ) : ?>
		<snf:advertisement>
			<?php
			while ( have_smartformat_sponsored_links() ) :
				$sponsored_link = get_smartformat_sponsored_link();
			?>
			<snf:sponsoredLink
				title="<?php echo esc_attr($sponsored_link['title']) ?>"
				link="<?php echo esc_attr($sponsored_link['link']) ?>"
				thumbnail="<?php echo esc_attr($sponsored_link['thumbnail']) ?>"
				advertiser="<?php echo esc_attr($sponsored_link['advertiser']) ?>" />
			<?php endwhile; ?>
			<?php if ( has_smartformat_adcontent() ) : ?>
			<snf:adcontent><![CDATA[<?php the_smartformat_adcontent(); ?>]]></snf:adcontent>
			<?php endif; ?>
		</snf:advertisement>
		<?php endif; ?>

		<?php if ( has_smartformat_analytics() ) : ?>
		<snf:analytics><![CDATA[<?php the_smartformat_analytics(); ?>]]></snf:analytics>
		<?php endif; ?>

		<?php
		/**
		 * Fires at the end of each SmartFormat feed item.
		 *
		 * @since 2.0.0
		 */
		do_action( 'smartformat_item' );
		?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
