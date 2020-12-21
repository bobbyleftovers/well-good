<?php


function the_vote_ratio( $campaign_id, $post_id = false, $force = false ) {
  echo \WG\Services\Rating::get_vote_ratio( $campaign_id, $post_id, $force );
}