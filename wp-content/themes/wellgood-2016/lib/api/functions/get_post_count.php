<?php


/**
 * Get the count of the published posts within a specified number of days
 * @param int $day_count - Find posts published within the day_count
 * @return int $limit - Number of posts from the last week
 */
function get_post_count( $days, $published = true ) {
  global $post;
  $timeframe = gmdate("Y-m-d", strtotime(date('jS F Y', time() + (60 * 60 * 24 * -$days) )));
  $posts = array(
    'posts_per_page' => -1,
    'post_type' => 'post',
    'orderby' => 'date',
    'order' => 'DESC',
    'date_query' => array(
      array(
          'after' => $timeframe,
          'inclusive' => true,
      ),
    ),
  );
  if ($published) :
    $posts['post_status'] = 'publish';
  endif;

  $limit = count(get_posts($posts));
  return $limit;
}