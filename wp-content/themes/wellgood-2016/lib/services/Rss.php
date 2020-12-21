<?php
namespace WG\Services;
class Rss {

  function __construct() {

     // Add new RSS endpoint for MSN feed
     add_action('init', array($this,'add_msn_feed'));

     // Add new RSS endpoint for slack feed
     add_action('init', array($this,'add_slack_feed'));

    //store rss feed url
    add_filter('acf/load_field/name=rss-feed-url', array($this,'acf_rss_load'));
    add_filter('acf/update_value/name=rss-feed-url', array($this,'acf_rss_value'), 10, 3);

    //Custom rss2 feed template
    remove_all_actions( 'do_feed_rss2' );
    add_action( 'do_feed_rss2', array($this,'rss2_template'), 10, 1 );
  }

  function add_msn_feed(){
    /**
     * A function to prepare and frame RSS content as per Vendor requirements
     */
    $feeds = array(
      'msn-article-posts',
      'msn-video-posts',
      'msn-slideshow-posts',
      'mailing-list'
    );

    foreach( $feeds as $feed ) {
      add_feed(
        "$feed",
        function() use ($feed) {
          include(get_template_directory() . "/templates/feeds/$feed.php");
        }
      );
    }
  }


  function add_slack_feed() {
    add_feed('slack', function(){
      include(get_template_directory() . "/templates/feeds/slack-feed.php");
    });
  }

  /**
  * Add ACF RSS feed URL
  */
  function acf_rss_load( $field ) {
    $field['readonly'] = 1;

    return $field;
  }

  function acf_rss_value( $value, $post_id, $field ) {
    global $post;
    $id = $post->ID;
    $value = get_site_url() . '/feed/mailing-list?id=' . $id;

    return $value;
  }

  function rss2_template(){
    $template = apply_filters('wg_rss2_template', WPINC.'/feed-rss2.php');
    include($template);
  }
}

