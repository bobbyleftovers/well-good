<?php

namespace WG\Settings;

use WG\Schema\Taxonomies\Category;

class  Sitemap {

    private $target_term_slug = 'good-sweat';
    private $sitemap_slug = 'sitemap-redirects';
    private $query_var = 'sitemap-redirect';
    private $target_term = null;
    private $links_per_page = 150;
    private $skip_wg_rewrite_rules_generated = false;
    private $redirected_posts = null;
    private $page = array(
      'from' => null,
      'to' => null
    );

    function __construct( $add_hooks = true ){

      if(!$add_hooks) return;

      add_action('generate_rewrite_rules', array($this,'generate_rewrite_rules'));
      add_filter('query_vars', array($this,'query_vars'), 1);
      add_action('template_redirect', array($this,'template_redirect'));
      add_filter('wg_copyright', array($this,'inject_footer_link'), 1);

    }

    /**
     *
     * Query the posts
     *
     */
    function get_redirected_posts($offset = null){

      if($this->redirected_posts) return $this->redirected_posts;

      if(!$offset){
        if($this->page_id !== -1){
          $offset = $this->links_per_page * ($this->page_id-1);
        } else {
          $offset = 0;
        }
      }

      $this->redirected_posts = new \WP_Query([
        'post_type' => array('post', 'recipe'),
        'offset' => $offset,
        'posts_per_page' => $this->links_per_page,
        'status' => 'publish'
      ]);

      return $this->redirected_posts;
    }


    /**
     *
     * Inject sitemap link on the footer
     *
     */
    public function inject_footer_link($content){
      $content = trim($content);
      $content.= '<p class="sitemap-link"><a href="'.get_home_url().'/'.$this->sitemap_slug.'">Sitemap redirect</a></p>';
      return  str_replace('</p><p class="sitemap-link">', ' &nbsp;&nbsp;&nbsp; ', $content);
    }

    /**
    *  Routing rules
    */
    function generate_rewrite_rules($wp_rewrite) {
        $rules = array();

        $rules['^'.$this->sitemap_slug.'/?$'] = 'index.php?'.$this->query_var."=-1";
        $rules['^'.$this->sitemap_slug.'/([^/]+)/?$'] = 'index.php?'.$this->query_var.'=$matches[1]';

        $wp_rewrite->rules = $rules + $wp_rewrite->rules;
    }

    /**
    *  Routing query vars
    */
    function query_vars($query_vars){
        $query_vars[] = $this->query_var;
        return $query_vars;
    }

    /**
    *  Routing template
    */
    function template_redirect(){

        $var = get_query_var( $this->query_var );
        if(!$var) return;

        if(is_numeric($var)){
          $this->page_id = (int) $var;
          $offset = null;
        } else {
          $from_to = explode('-',$var);
          $from = (int) $from_to[0];
          $to = (int) $from_to[1];
          $this->links_per_page = $to - $from + 1;
          $current_page = ceil($to / $this->links_per_page);
          $this->page_id = (int) $current_page;
          $offset = $from -1;
          $this->page['from'] = $from;
          $this->page['to'] = $to;
        }

        $query = $this->get_redirected_posts($offset);

        $this->total_links = (int) $query->found_posts;
        $this->total_pages = (int) ceil($this->total_links / $this->links_per_page);

        include get_template_directory() . '/templates/page-sitemap.php';

        die;
    }

    /**
    *  Print page content
    */
    public function get_page_title(){

      if($this->page_id == -1){
        return 'Sitemap Redirects';
      } else {
        $from = $this->page['from'] ?? $this->links_per_page * ($this->page_id-1) + 1;
        if($this->page_id >= $this->total_pages){
            $to = $this->total_links;
          } else {
            $to = $this->page['to'] ?? $this->page_id * $this->links_per_page;
        }
        return 'Sitemap Redirects (From '.$from.' to '.$to.')';
      }
    }

    /**
    *  Print page content
    */
    public function get_page_links(){
      if($this->page_id == -1){
        return $this->get_sitemap_index_links();
      } else {
        return $this->get_sitemap_page_posts_links();
      }
    }

    /**
    *  Print sitemap index links
    */
    public function get_sitemap_index_links(){
      $links = array();
      $home_url = get_home_url();
      for ($k = 1 ; $k < ($this->total_pages + 1); $k++){
        if($k === $this->total_pages){
          $current_total_links = $this->total_links;
        } else {
          $current_total_links = $k * $this->links_per_page;
        }
        $links[] = array(
          'url' => $home_url.'/'.$this->sitemap_slug.'/'.$k,
          'text' => 'Sitemap redirects (from '.(($k-1) * $this->links_per_page + 1).' to '.($current_total_links).')'
        );
      }
      return $links;
    }

    /**
    *  Print sitemap page links
    */
    public function get_sitemap_page_posts_links(){

      $query = $this->get_redirected_posts();

      $links = array();

      foreach($query->posts as $post){

        $permalink = get_permalink($post);
        if (!$post->post_name || $post->post_name == '' || !strpos($permalink, $post->post_name)) continue;
        $old_permalink = str_replace($post->post_name, wg_get_post_primary_category($post->ID)->slug.'/'.$post->post_name, $permalink);

        $links[] = array(
          'url' => $old_permalink,
          'text' => $old_permalink
        );
      }

      return $links;
    }
}
