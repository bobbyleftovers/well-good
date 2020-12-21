<?php
global $post;

/**
 * RSS2 Feed Template for displaying RSS2 Mailing List feed.
 *
 * @package WordPress
 */

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
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
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
  xmlns:media="http://search.yahoo.com/mrss/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>

	<channel>
		<title><?php wp_title_rss(); ?></title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss("description") ?></description>
		<lastBuildDate><?php
			$date = get_lastpostmodified( 'GMT' );
			echo $date ? mysql2date( 'r', $date, false ) : date( 'r' );
		?></lastBuildDate>
		<language><?php bloginfo_rss( 'language' ); ?></language>
		<sy:updatePeriod><?php
			$duration = 'hourly';

			/**
			 * Filters how often to update the RSS feed.
			 *
			 * @since 2.1.0
			 *
			 * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
			 *                         'yearly'. Default 'hourly'.
			 */
			echo apply_filters( 'rss_update_period', $duration );
		?></sy:updatePeriod>
		<sy:updateFrequency><?php
			$frequency = '1';

			/**
			 * Filters the RSS update frequency.
			 *
			 * @since 2.1.0
			 *
			 * @param string $frequency An integer passed as a string representing the frequency
			 *                          of RSS updates within the update period. Default '1'.
			 */
			echo apply_filters( 'rss_update_frequency', $frequency );
		?></sy:updateFrequency>
		<?php
		/**
		 * Fires at the end of the RSS2 Feed Header.
		 *
		 * @since 2.0.0
		 */
		do_action( 'rss2_head');

		if ( isset($_GET['id']) && is_numeric($_GET['id']) ) {
			$id_param = (int) $_GET['id'];
			$ids = get_field( 'mailing-list', $id_param );
		} else {
			$ids = '';
    }

		foreach($ids as $id) :
			$post = $id;
      setup_postdata($post);
      $partner = NULL;

      try {
        if ( article_is_branded(get_the_ID()) ) {
          $partners = get_the_terms( get_the_ID(), 'partners');
          if(!is_wp_error($partners) && !empty($partners)) {
            $partner = $partners[0];
          }
        }
      } catch (\Throwable $th) {}
      $featured_image = NULL;
      if ($alt_image_field = get_field('alternate_image')) {
        if (!empty($alt_image_field) && isset($alt_image_field['url'])) {
          $featured_image = $alt_image_field['url'];
        }
      }

      if (has_post_thumbnail() && $featured_image === NULL) {
        $featured_image = get_the_post_thumbnail_url();;
      }
  ?>
			<item>
				<title><?php the_title_rss(); ?></title>
				<link><?php the_permalink_rss() ?></link>
				<?php if ( get_comments_number() || comments_open() ) : ?>
					<comments><?php comments_link_feed(); ?></comments>
				<?php endif; ?>
				<?php if ( isset($featured_image) && $featured_image ) : ?>
					<media:content medium="image" url="<?= $featured_image; ?>"/>
				<?php endif; ?>
				<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>

        <?php if ( $partner && isset($partner->name) ) :?>
					<partner><?= $partner->name; ?></partner>
				<?php endif; ?>
				<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
				<?php the_category_rss('rss2') ?>

				<guid isPermaLink="false"><?php the_guid(); ?></guid>
				<?php if (get_option('rss_use_excerpt')) : ?>
					<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
				<?php else : ?>
					<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
					<?php $content = get_the_content_feed('rss2'); ?>
					<?php if ( strlen( $content ) > 0 ) : ?>
						<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
					<?php else : ?>
						<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( get_comments_number() || comments_open() ) : ?>
					<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
					<slash:comments><?php echo get_comments_number(); ?></slash:comments>
				<?php endif; ?>
				<?php rss_enclosure(); ?>
				<?php
				/**
				 * Fires at the end of each RSS2 feed item.
				 *
				 * @since 2.0.0
				 */
				do_action( 'rss2_item' );
				?>
			</item>
		<?php
		endforeach;
		wp_reset_postdata(); ?>
	</channel>
</rss>
