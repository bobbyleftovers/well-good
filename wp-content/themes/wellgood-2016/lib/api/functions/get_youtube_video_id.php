<?php
/**
 * Get YouTube video ID
 *
 * Extract video ID from YouTube URL
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 11.0.0
 */

// Ref: https://stackoverflow.com/questions/2936467/parse-youtube-video-id-using-preg-match
function get_youtube_video_id( $url ) {
  if ( empty( $url ) ) :
    return '';
  endif;

  if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
    return empty($match[1]) ? FALSE : $match[1];
  }

  return FALSE;
}
