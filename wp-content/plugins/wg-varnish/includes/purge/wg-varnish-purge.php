<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Abstract purge class
 *
 *
 * @since      1.0.1
 * @package    WG_Varnish
 * @subpackage WG_Varnish/purge
 * @author     Leaf & barrel
 */

class WG_Varnish_Purge {

   /**
	 * Root url
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      string
	 */
  protected $root_url;

  /**
	 * Skip purge based on host/env
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      bool
	 */
  protected $skip_env;

  /**
	 * Force purge
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      bool
	 */
  protected $forced_purge;

  /**
	 * URL notice admin
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      object
	 */
  protected $purged_urls = array();
  
  /**
	* Servers notice admin
	*
	* @since    1.0.1
	* @access   protected
	* @var      object
	*/
  protected $purged_servers = array();


  /**
  * 
  * Skip purge
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function skip_purge(){

    if(isset($this->forced_purge) && $this->forced_purge) return false;

    if( !isset($this->skip_env) ){
      if($this->is_production()) $this->skip_env = apply_filters('wg_varnish_purge_skip_production', false, $this->get_host());
      else $this->skip_env = apply_filters('wg_varnish_purge_skip_staging', true, $this->get_host());
    }

    return $this->skip_env;

  }

  /**
  * 
  * Force purge
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function force_purge($force = true){

    $this->forced_purge = $force;

  }

  /**
  * 
  * Get Leaf Varnish Servers
  *
  * @since      0.0.1
  * @author     Leaf
  */
  public function get_varnish_servers() {
    // Retrieve the IPs for each varnish server
    $ch = curl_init('https://lbq.leaf.io/pool/members/wellandgood-'.($this->is_production() ? 'external' : 'internal').'-varnish-80');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curl_output = curl_exec($ch);
    $varnish_servers = json_decode($curl_output, true);

    if ($varnish_servers && $varnish_servers['members'] ) {
      return $varnish_servers['members'];
    }

    return [];
  }

  /**
  * 
  * Purge array of URLs
  *
  * @since      0.0.1
  * @author     Leaf
  */
  public function purge_urls($urls, $force = false, $add_notice = true, $log = true){

    if(!$urls || sizeof($urls) === 0) return false;

    if ( defined( 'DOING_CRON' ) ) $add_notice = false;

    if( $this->forced_purge && $force) $force = false;

    if($force) $this->force_purge();
    if($this->skip_purge()) return;

    // Remove duplicates if there happen to be any.
    $urls = array_unique($urls);

    // Trim & clean URLs
    foreach($urls as $key => $url){
      $clean_url = str_replace( $this->get_root_url(), '', $url );
      $clean_url = str_replace('//', '/', $clean_url);
      $urls[$key] = $clean_url;
    }

    // Response
    $response = array();

    // Loop through each server and purge the URLs
    $varnish_servers = $this->get_varnish_servers();

    foreach ($urls as $url) {
      $log_secondary = array();
      foreach ($varnish_servers as $server_ip) {
        if(!isset($response[$server_ip])) $response[$server_ip] = array();
        $response[$server_ip][$url] = $this->purge_varnish($server_ip, $url);
        $log_secondary[] = $server_ip;
      }
      //add log
      if($log) $this->log('purge_url', $url, $log_secondary);
    }

    if($force) $this->force_purge(false);

    if($add_notice) $this->add_notice_data($varnish_servers, $urls);

    return $response;
  }

  /**
  * 
  * Add notice data
  *
  * @since      1.0.1
  * @author     Barrel
  */

  function add_notice_data( $varnish_servers, $urls ){

    if(!apply_filters( 'wg_varnish_notices', true)) return;

    $notice = get_option('notice_wg_varnish', array('purged_servers' => array(), 'purged_urls' => array()));

    $notice_new = array( 
      'purged_urls' => array_unique(array_merge($notice['purged_urls'],$urls)), 
      'purged_servers' => array_unique(array_merge($notice['purged_servers'],$varnish_servers))
    );

    update_option( 'notice_wg_varnish', $notice_new);
  }


  /**
  * 
  * Purge one URL
  *
  * @since      0.0.1
  * @author     Leaf
  */

  public function purge_varnish( $ip, $url, $log = true ) {

    // Skip
    if($this->skip_purge()) return;

    // Curl request
    $host = $this->get_host();
    $ch = curl_init('http://'.$ip.$url.'?reload=1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: '.$host));
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    curl_close($ch);

    // Response
    return $resp;
  }

  /**
  * 
  * Environment check
  *
  * @since      1.0.0
  * @author     Leaf
  */
  public function is_production( ) {
      //test theme function if exists
      if(function_exists('is_production')){
        return is_production();
      }
      //leaf
      if(isset($_SERVER['STG_HOST'])) return !(!$_SERVER['IS_PRODUCTION'] || $_SERVER['IS_PRODUCTION'] !== 'true') ;
      //pantheon
      return !isset($_SERVER['LANDO']);
  }

  /**
  * 
  * Gets public host
  *
  * @since      1.0.2
  * @author     Barrel
  */
  public function get_host(){

    if(isset($_SERVER['STG_HOST'])) return $_SERVER['STG_HOST'];

    return $_SERVER['SERVER_NAME'];
  }

  /**
  * 
  * Schedule secondary purge cronjob
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function schedule_secondary_purge( $hook = 'wg_varnish_secondary_purge', $args = array(), $delay = 1 ) {

    wp_schedule_single_event( time() + $delay, $hook, $args );

  }

  /**
  * 
  * Get home url
  *
  * @since      1.0.2
  * @author     Barrel
  */
  function get_root_url(){
    if(isset($this->root_url) && $this->root_url) return $this->root_url;
    
    $this->root_url = home_url();

    return $this->root_url;
  }

  /**
  * 
  * Log method
  *
  * @since      1.3.0
  * @author     Barrel
  */
  
  private function log($type, $main, $secondary){

   return $this->loader->load('SQL/WG_Varnish_SQL_Logs')->new_log($type, $main, $secondary);

  }

  

}