<h2 class="h1">Custom redirections sitemap</h2>
<?php

for ($k = 1 ; $k < ($total_pages + 1); $k++){ 
        if($k === $total_pages){
                $current_total_links = $total_links;
        } else {
                $current_total_links = $k * $this->links_per_page;
        }
        echo '<a href="/'.$this->get_sitemap_slug().'/'.$k.'" target="_self">Sitemap custom redirects (from '.(($k-1) * $this->links_per_page + 1).' to '.($current_total_links).')</a><br>';
}

?>