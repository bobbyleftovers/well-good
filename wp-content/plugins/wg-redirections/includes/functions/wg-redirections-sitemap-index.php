<?php

function wg_redirections_sitemap_index(){
    include_once dirname(dirname(__FILE__)).'/front/class-wg-redirections-sitemap.php';
    $sitemap = new \WG_Redirections_Sitemap();
    $sitemap->print_sitemap_index();
}