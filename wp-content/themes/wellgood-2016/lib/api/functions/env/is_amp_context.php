<?php

function is_amp_context() {
  return defined( 'AMP_QUERY_VAR' ) && get_query_var( AMP_QUERY_VAR, false ) === 1;
}