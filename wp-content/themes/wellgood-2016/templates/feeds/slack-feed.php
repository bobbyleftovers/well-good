<?php
$post_count = get_post_count(7);
$posts = query_posts('showposts=' . $post_count);
$cats = get_the_category();
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0" 
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
<channel>
    <title><?php bloginfo_rss('name'); ?></title>
    <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
    <link><?php bloginfo_rss('url') ?></link>
    <description><?php bloginfo_rss('description') ?></description>
    <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
    <language><?php echo bloginfo_rss( 'language' ); ?></language>
    <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
    <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
    <?php do_action('rss2_head'); ?>
    <?php while(have_posts()) : the_post(); ?>
    <item>
        <title><?php the_title_rss(); ?></title>
        <link><?php the_permalink_rss(); ?></link>
        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
        <dc:creator><?php the_author(); ?></dc:creator>
        <?php foreach($cats as $cat) : ?>
            <category><?php echo $cat->name; ?></category>
        <?php endforeach; ?>
        <guid isPermaLink="false"><?php the_guid(); ?></guid>
        <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
        <?php $content = get_the_content_feed('rss2'); ?>
        <?php if ( strlen( $content ) > 0 ) : ?>
            <content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
        <?php else : ?>
            <content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
        <?php endif; ?>
        <?php rss_enclosure(); ?>
        <?php do_action('rss2_item'); ?>
    </item>
    <?php endwhile; ?>
</channel>
</rss>