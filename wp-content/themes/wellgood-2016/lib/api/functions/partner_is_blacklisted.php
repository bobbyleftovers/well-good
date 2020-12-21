<?php

function partner_is_blacklisted($post_id) {
  return WG\Schema\Taxonomies\Partners::partner_is_blacklisted($post_id);
}