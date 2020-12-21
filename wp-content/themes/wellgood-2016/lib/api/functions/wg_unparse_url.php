<?php
/**
 * Reverse parse_url
 *
 * @param array $url - parse_url generated back to a url string
 * @return string $url - Truncated text
 */
function wg_unparse_url($url) {
  $scheme = isset($url["scheme"]) ? $url["scheme"] . ":" : "";
  $user = isset($url["user"]) ? rawurlencode($url["user"]) : "";
  $pass = isset($url["pass"]) ? ":" . rawurlencode($url["pass"]) : "";
  $at = strlen($user.$pass) ? "@" : "";
  $host = isset($url["host"]) ? rawurlencode($url["host"]) : "";
  $double_slash = strlen($at.$host) ? "//" : "";
  $port = isset($url["port"]) ? ":" . $url["port"] : "";
  $path = isset($url["path"]) ? $url["path"] : "";
  $query = isset($url["query"]) ? "?" . $url["query"] : "";
  $fragment = isset($url["fragment"]) ? "#" . $url["fragment"] : "";
  return $scheme.$double_slash.$user.$pass.$at.$host.$port.$path.$query.$fragment;
}
