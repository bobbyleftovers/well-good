<?php

function get_datalayer_data($id = null, $type = 'standard', $is_ajax = false, $infinite_instance = null) {
 return \WG\Content\Datalayer::get_datalayer_data($id, $type, $is_ajax, $infinite_instance);
}