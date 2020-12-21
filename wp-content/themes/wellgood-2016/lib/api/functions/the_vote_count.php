<?php

function the_vote_count() {
  echo \WG\Services\Rating::get_vote_count();
}
