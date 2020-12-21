<?php

function wg_esc_url($string) {
  return htmlspecialchars(rawurlencode(html_entity_decode(wp_strip_all_tags($string, ENT_COMPAT, 'UTF-8'))));
}