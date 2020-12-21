<?php

/**
 *  Load traits
 */
include_once dirname(dirname(__FILE__)).'/core/trait-wg-redirections-core.php';


/**
 * Redirections Sitemap
 */
class WG_Redirections_Sitemap {

    use WG_Redirections_Core;

    private $links_per_page = 150;
    private $sitemap_slug = null;

    /**
    *  Routing rules
    */
    function generate_rewrite_rules($wp_rewrite) {
        $rules = array();

        $rules['^'.$this->get_sitemap_slug().'/?$'] = 'index.php?'.$this->get_query_var()."=-1";
        $rules['^'.$this->get_sitemap_slug().'/(\d+)/?$'] = 'index.php?'.$this->get_query_var().'=$matches[1]';
  
        $wp_rewrite->rules = $rules + $wp_rewrite->rules;  
    }

    /**
    *  Routing query vars
    */
    function query_vars($query_vars){
        $query_vars[] = $this->get_query_var();
        return $query_vars;
    }

    /**
    *  Routing template
    */
    function template_redirect(){

        $var = get_query_var( $this->get_query_var() );
        if(!$var) return;

        $page_id = (int) $var;

        wp_enqueue_style( $this->plugin_name . '-sitemap-front', $this->get_scripts_uri() .'css/sitemap-front.css', [], $this->version, 'all' );

        if ( $page_id === -1 ) {
            include plugin_dir_path( dirname(dirname(__FILE__) ) ) . 'templates/sitemap-index.php';
        } else {
            include plugin_dir_path( dirname(dirname(__FILE__) ) ) . 'templates/sitemap-page.php';
        }

        die;
    }

    /**
    *  Get query var for routing
    */
    function get_query_var(){
        return $this->plugin_name.'-sitemap';
    }

    /**
    *  Get pages slug
    */
    function get_sitemap_slug(){
        if(!$this->sitemap_slug) $this->sitemap_slug = $this->plugin_name.'-sitemap';
        return $this->sitemap_slug;
    }


    /**
    *  Count active redirections
    */
    public function count_redirections_sitemap( ) {

        // Connect DB
        if(!$this->mysqli) if($this->connect_mysqli()->connect_errno) return;
        
        $sql = "SELECT * FROM ".$this->get_table_name()." WHERE is_active = 1 AND add_to_sitemap = 1";

		$result = $this->mysqli->query($sql);

		if(!$result) return 0;

		return $result->num_rows;
    }

    /**
    *  Get page links redirections
    */
    public function get_page_links_redirections($offset = 0) {

        // Connect DB
        if(!$this->mysqli) if($this->connect_mysqli()->connect_errno) return;
        
        $sql = "SELECT * FROM ".$this->get_table_name()." WHERE is_active = 1 AND add_to_sitemap = 1 LIMIT ".$offset.", ".$this->links_per_page;

		$result = $this->mysqli->query($sql);

        if(!$result) return array();

        $redirections_links = array(); 

        $home_url = get_home_url();
        
        while ($obj = $result->fetch_assoc()) {
            $obj['link_source'] = $home_url.$obj['source_uri'];
            if($obj['type'] === 'external'){
                $obj['link_target'] = $obj['target_uri'];
            } else {
                $obj['link_target'] = $home_url.$obj['target_uri'];
            }
            $obj['title'] = $obj['link_source']. ' redirects to ' .$obj['link_target'];
            $redirections_links[] = $obj;
        }
        
        $result -> free_result();

		return $redirections_links;
    }
    
    /**
    *  Print sitemap index links
    */
    public function print_sitemap_index(){
        $total_links = (int) $this->count_redirections_sitemap();
        $total_pages = (int) ceil($total_links / $this->links_per_page);
        if($total_pages) include dirname( dirname( __FILE__ ) ) . '/front/views/sitemap-index.php';
    }

    /**
    *  Print sitemap page links
    */
    public function print_sitemap_page(){
        $total_links = (int) $this->count_redirections_sitemap();
        $total_pages = (int) ceil($total_links / $this->links_per_page);
        $current_page = get_query_var( $this->get_query_var() );
        $from = $this->links_per_page * ($current_page-1) + 1;
        if($current_page == $total_pages){
            $to = $total_links;
        } else {
            $to = $current_page * $this->links_per_page;
        }
        $page_links = $this->get_page_links_redirections($from-1);
        include dirname( dirname( __FILE__ ) ) . '/front/views/sitemap-page.php';
    }
}