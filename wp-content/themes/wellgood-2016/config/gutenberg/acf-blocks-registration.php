<?php

$blocks = array_merge(
  include('acf-blocks/common.php'), 
  include('acf-blocks/layout.php'),
  include('acf-blocks/trends-2021.php'),
  include('acf-blocks/renew-year-2021.php')
);

return array( 
  'acf_blocks' => $blocks
);