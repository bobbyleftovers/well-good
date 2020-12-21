<?php

namespace WG\Services;

class MSN_Email_Automation {

  function __construct(){
    add_action( 'msn_email_automation_hook', array($this,'msn_send_email') );
  }

  /**
  * Send the email
   */

  function msn_send_email() {
    if( "live" !== $_SERVER['PANTHEON_ENVIRONMENT'] ) {
      exit();
    }

    $contents = $this->msn_get_email_contents();
    $date = date("m-d-Y");
    $subject = "New Well+Good Content: $date";
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $recipients_option = get_field('msn_email_recipients', 'option');
    $recipients = array();

    foreach( $recipients_option as $key => $email ) {
      array_push($recipients, $email['email_address']);
    }

    wp_mail($recipients, $subject, $contents, $headers);
  }

  /**
   * Prepare the email contents as a string of HTML.
   * @return string HTML to be in the email
  */

  function msn_get_email_contents() {
    $contents = "";

    $post_args = array(
      'post_type' => 'post',
      'posts_per_page' => 50,
      'post_status' => 'publish',
      'date_query' => array(
        array(
          'after' => '24 hours ago'
        )
      ),
      'tag__not_in' => array(
        6686, // 'video' tag
        6716 // 'branded' tag -- temporarily excluded for testing
      )
    );

    $slideshow_args = array(
      'post_type' => 'slideshow',
      'posts_per_page' => 50,
      'post_status' => 'publish',
      'date_query' => array(
        array(
          'after' => '24 hours ago'
        )
      ),
      'tag__not_in' => array(
        6686, // 'video' tag
        6716 // 'branded' tag -- temporarily excluded for testing
      )
    );

    $posts = get_posts($post_args);
    $slideshows = get_posts($slideshow_args);
    $video_data = get_brightcove_videos('24 hours');

    $contents .= $this->msn_get_email_section_content("Articles", $posts);
    $contents .= $this->msn_get_email_section_content("Slideshows", $slideshows);
    $contents .= $this->msn_get_email_section_content("Videos", $video_data->videos);

    return $contents;
  }

  /**
   *
   * Helper function to prepare HTML for each section of the email
   *
   * @param string $title Title of the section
   * @param array $posts Array of posts or videos
   *
   * @return string String of HTML for that section
   */

  function msn_get_email_section_content($title, $posts) {
    $contents = "";
    $contents .= "<h3>$title</h3>";
    $contents .= "<ul>";

    foreach ($posts as $post) {
      $item_title = $title == "Videos" ? $post->name : $post->post_title;

      if( $title == "Articles" ) {
        $category = get_the_category( $post->ID );
        $item_permalink = get_the_permalink($post->ID);
        $item_category = '- ' . $category[0]->name . ' - <a href="' . $item_permalink . '" target="_blank">' . $item_permalink . '</a>';
      } else {
        $item_category = '';
      }

      $contents .= "<li>$item_title $item_category</li>";
    }

    $contents .= "</ul>";
    return $contents;
  }

}