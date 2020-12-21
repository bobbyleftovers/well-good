<?php

function include_partial($name, $vars = array()) {
  include(get_template_directory() . "/templates/partials/$name.php");
}