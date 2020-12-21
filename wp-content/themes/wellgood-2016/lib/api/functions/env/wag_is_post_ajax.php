<?php

/**
 * Check whether or not the page we're loading is the first page.
 * This is primarily used to identify whether or not we're loading a post over AJAX (infinite scroll)
 * @return bool False if is not first page
 */
function wag_post_is_ajax() {
  return !is_int(get_query_var('paged'));
}