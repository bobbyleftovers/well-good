<?php

namespace WG\Tools;

class Backward_Compatibility {
  
  static private $next_schedule = null;
  static private $state = null;
  static private $option = 'backward_compatibility_action';
  static private $cron_hook = 'backward_compatibility_cron';
  static $form_action = 'tools.php?page=backward-compatibility';
  static $cron_intervals = array(
    'five_seconds' => array(
      'interval' => 5,
      'display'  => '5 seconds',
    ),
    'ten_seconds' => array(
      'interval' => 10,
      'display'  => '10 seconds'
    ),
    'thirty_seconds' => array(
      'interval' => 30,
      'display'  => '30 seconds',
      'default' => true
    ),
    'one_minute' => array(
      'interval' => 60,
      'display'  => 'One minute',
    )
  );


  /*===============================
  *                               *
  *     Actions and filters       *
  *                               *
  ================================*/

  function __construct(){
    add_action('admin_menu', array($this,'admin_menu'));
    add_action( self::$cron_hook, array($this,'run_interval') );
    add_filter( 'cron_schedules', array($this,'add_cron_intervals') );
  }



 /*===============================
  *                               *
  *     Manage posts              *
  *                               *
  ================================*/


  /*
  *  Get query params
  */
  static function build_query( $args = null ) {
    $defaults = array(
        'numberposts'      => 5,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'post',
        'suppress_filters' => true,
    );
    
    return wp_parse_args( $args, $defaults );
  }

  /*
  *  Get posts
  */
  static function get_posts(){
    $state = self::get_state();
    return get_posts(self::build_query($state['query']));
  }


  /*
  *  Update single post
  */
  static function update_post($incoming_post){

    //skip if already parsed
    $state = self::get_state();
    $last_id = get_post_meta( $incoming_post->ID, 'bc_id', true );
    if($last_id && $last_id === $state['id']) return;

    //parse
    global $post;  
    $post = get_post($incoming_post->ID); // set global post to avoid errors in other scripts
    $state = self::get_state();
    $post->post_content = $post->post_content;
    wp_update_post($post);
    wp_set_object_terms($incoming_post->ID, 'bc-tool', 'dev_tag', true);  
    update_metadata( 'post', $incoming_post->ID, 'bc_id', $state['id'] );
    $state['query']['offset']++;
    if( $state['query']['offset'] >= $state['total_posts']) {
      self::stop_tool();
      return false;
    }

    //update state
    $state['total_posts_parsed']++;
    $state['last_post_id'] = $post->ID;
    self::update_state($state);
    return true;
  }


  /*===============================
  *                               *
  *     Control state             *
  *                               *
  ================================*/

  /*
  *  Update db option with state data
  */
  static function update_state($data){
    $data['last_update'] = time();
    self::$state = $data;
    update_option(self::$option, $data);
  }

   /*
  *  Set values in state data
  */
  static function set_state($data, $state = false){
    if(!$state) $state = self::get_state();
    self::update_state(array_merge($state,$data));
  }


  /*
  *  Gets saved state data
  */
  static function get_state($refetch = false){
    if(self::$state !== null && !$refetch) return self::$state;
    self::$state = get_option(self::$option);
    return self::$state;
  }

  /*
  *  Start tool
  */
  static function start_tool($data){
    $count = 0;
    $initial_offset = $data['query']['offset'];
    foreach($data['query']['post_type'] as $post_type){
      $count += wp_count_posts($post_type)->publish;
    }
    if($count == 0) {
      self::notice('There are no posts for that combination of post types');
      return;
    }
    $data_default = array(
      'total_posts_with_offset' => $count,
      'total_posts' => $count - $initial_offset,
      'total_posts_parsed' => 0,
      'total_intervals_parsed' => 0,
      'initial_offset' => $initial_offset,
      'user_id' => get_current_user_id(),
      'total_intervals_initialized' => 0,
      'interval_is_running' => false,
      'id' => uniqid()
    );
    self::update_state(array_merge($data_default,$data));
    self::start_cron($data['schedule']);
  }

  /*
  *  Stops tool
  */
  static function stop_tool(){
    self::$state = false;
    delete_option(self::$option);
    self::stop_cron();
  }

   /*
  *  Checks if tool is running
  */
  static function is_running($state = false){
    if(!$state) $state = self::get_state(true);
    if(!$state) return false;
    return true;
  }



  /*================================
  *                                *
  *     Interval                   *
  *                                *
  ================================*/

  /*
  *  Update posts to trigger on save actions and filters
  */
  static function run_interval(){

    //get state
    $state = self::get_state();

    //get current user
    $current_user_id = get_current_user_id();

    //set task user
    wp_set_current_user( $state['user_id'] );

    // start interval
    if(!self::start_interval()) return;

    // loop posts interval
    foreach(self::get_posts() as $post){
      self::update_post($post);
    }

    // end interval
    self::end_interval();

    // reset current user
    if($current_user_id) wp_set_current_user($current_user_id);
  }

  /*
  *  Start interval
  */
  static function start_interval(){
    $state = self::get_state();
    if(!self::is_running($state) || $state['interval_is_running']) return false;
    self::set_state(array(
      'total_intervals_initialized' => $state['total_intervals_initialized']++,
      'interval_is_running' => true
    ), $state);
    return true;
  }

  /*
  *  End interval
  */
  static function end_interval(){
    $state = self::get_state(true);
    if(!self::is_running($state)) return;
    $state['interval_is_running'] = false;
    $state['total_intervals_parsed']++;
    self::update_state($state);
    self::force_cron_job();
  }



  /*===============================
  *                                *
  *     Cron                       *
  *                                *
  ================================*/

  /**
	* Get cron next schedule
  */
	static function get_schedule() {
    if(self::$next_schedule !== null) return self::$next_schedule;
    self::$next_schedule = wp_next_scheduled( self::$cron_hook );
    return self::$next_schedule;
  }

  /**
	* Get cron data
  */
  static function get_cron_data() {
    return self::$cron_intervals[self::get_state()['schedule']];
  }

  /**
	* Start cron job
  */
  static function start_cron($schedule = 'ten_seconds'){
    if(wp_next_scheduled(self::$cron_hook)){
      self::stop_cron();
    }
    wp_schedule_event(time(), $schedule, self::$cron_hook);
    self::force_cron_job();
  }

  /*
	* Stop all cron jobs
  */
  static function stop_cron(){
    wp_unschedule_event(wp_next_scheduled(self::$cron_hook), self::$cron_hook );
    wp_clear_scheduled_hook(self::$cron_hook);
  }

  /*
  *  Spawn/execute cron if running hooks active
  */
  static function force_cron_job($execute_now = false){
    if($execute_now) {
      self::run_interval();
    } else {
      return spawn_cron();
    }
    return false;
  }

  /*
  *  Adds cron intervals
  */
  static function add_cron_intervals( $schedules ) {
    foreach(self::$cron_intervals as $key => $interval){
      $schedules[$key] = array(
        'interval' => $interval['interval'],
        'display'  => esc_html__( $interval['display'] ),
      );
    }
    return $schedules;
  }


  /*===============================
  *                                *
  *     Admin                      *
  *                                *
  ================================*/

  /*
  *  Add Tool Page
  */

  function admin_menu(){
    if(current_user_can('administrator')) add_management_page( 'Backward Compatibility Tool', 'Backward Compatibility', 'manage_options', 'backward-compatibility', array( $this, 'admin_page' ) );
  }

  /*
  *  Admin Page
  */
  
  static function admin_page() {
    if(!current_user_can('administrator')) {
      echo "<div style='margin-top: 40px'>Sorry, you don't have enough permissions to visit that page</div>"; return;
    }
    date_default_timezone_set("America/New_York");
    self::$form_action = get_admin_url().self::$form_action;
    self::save_form();
    ?>
    <div class="wrap">
      <h1>Backward Compatibility Tool</h1>
      <?php 
        if(self::is_running()){
          self::admin_page_stop();
          self::print_schedule();
        } else {
          self::admin_page_start();
        }
      ?>
    </div>
    <?php 
    self::print_styles();
  }
 
  /*
  *
  *  Admin custom notice
  *
  */

  static function notice($content, $type = 'error') { ?>
      <div class="bc_notice bc_card bc_notice_<?=$type?>">
        <?= $content ?>
      </div>
  <?php }
  /*
  *
  *  UI Unactive
  *
  */

  static function admin_page_start() {
      ?>
      <form method="post" class="bc_form bc_card" action="<?=self::$form_action?>">
            <table class="form-table" style="max-width:980px;">
                <tr valign="top">
                  <td scope="row" style="padding-bottom:0; padding-left:0; vertical-align: top;">
                    <strong>Post types</strong>
                  </th>
                  <td scope="row" style="padding-bottom:0; padding-left:0; vertical-align: top;">
                    <strong>Posts per interval</strong><br>
                  </th>
                  <td scope="row" style="padding-bottom:0; padding-left:0; vertical-align: top;">
                    <strong>Initial offset</strong><br>
                  </th>
                  <td scope="row" style="padding-bottom:0; padding-left:0; vertical-align: top;">
                    <strong>Cronjob <br>recurrence</strong>
                  </th>
                </tr>
                <tr valign="top">
                  <td style="padding-left:0; vertical-align: top; padding-right: 10px;">
                  <div style="box-shadow: inset 0 1px 2px rgba(0,0,0,.07); width: calc(100% - 10px); max-height:200px; border: 1px solid rgb(221, 221, 221); overflow:auto; padding: 5px;">
                    <?php 
                      foreach(self::get_allowed_post_types() as $post_type): 
                         ?>
                        <div>
                          <input type="checkbox" id="post_type_<?=$post_type?>" name="query[post_type][]" value="<?=$post_type?>"
                          <?=$post_type === 'post' ? 'checked':''?>>
                          <label for="post_type_<?=$post_type?>"><?=$post_type?></label>
                        </div>
                      <?php 
                      endforeach; ?>
                    </div>
                  </td>
                  <td style="padding-left:0; vertical-align: top;">
                    <input name="query[numberposts]" type="number" min="1" max="500" value="30">
                  </td>
                  <td style="padding-left:0; vertical-align: top;">
                    <input name="query[offset]" type="number" min="0" value="0">
                  </td>
                  <td style="padding-left:0; vertical-align: top;">
                      <select name="schedule">
                          <?php 
                          foreach(self::$cron_intervals as $key => $interval): 
                          ?>
                            <option value="<?= $key ?>" <?= isset($interval['default']) && $interval['default'] ? 'selected':''?>><?= $interval['display'] ?></option>
                          <?php 
                          endforeach; ?>
                      </select>
                  </td>
                </tr>
            </table>
            <?php submit_button('Run Now', 'primary'); ?>
        </form>
    <?php 
  }


  /*
  *  Get allowed post types
  */
  static function get_allowed_post_types(){
    $types = get_post_types();
    $not_allowed = array(
      'revision',
      'nav_menu_item',
      'custom_css',
      'customize_changeset',
      'oembed_cache',
      'user_request',
      'wp_block',
      'amp_validated_url',
      'acf-field-group',
      'acf-field',
      'amn_exact-metrics',
      'social-post',
      'mailing_list'
    );
    return array_diff($types, $not_allowed);
  }

  /*
  *  Save form
  */
  static function save_form() {
    if(isset($_POST['query'])){
      self::start_tool(array(
        'query' => $_POST['query'],
        'schedule' => $_POST['schedule']
      ));
    } else if(isset($_POST['stop'])){
      self::stop_tool();
    } else if(self::is_running()) {
      self::force_cron_job(true);
      return;
    }
  }

  /*
  *  Print schedule
  */
  static function print_schedule(){ 
    if(!self::get_schedule()) return;
    ?>
    <small>
      <strong>Now:</strong> <?= date("h:i:sa") ?><br>
      <strong>Next interval:</strong> <?= date("h:i:sa",self::get_schedule()) ?><br>
      <strong>Throttling:</strong> <?= self::$cron_intervals[wp_get_schedule( self::$cron_hook )]['interval'] ?>s<br>
    </small>
  <?php }


  /*
  *  CSS styles
  */
  static function print_styles(){ ?>
    <style>
      .bc_form td > input,
      .bc_form td > select {
        width:100%;
      }
      .bc_card {
        background: white; 
        padding: 10px 20px 0; 
        margin-top: 20px; 
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); 
        display:block;
      }
      .bc_notice {
        border-left: 4px solid grey;
        padding-bottom:10px;
      }
      .bc_notice_error {
        border-left-color: red;
      }
    </style>
  <?php }

  /*
  *
  *  UI Task Active
  *
  */

  static function admin_page_stop() {
    $state = self::get_state();
    ?>
    <div class="notice notice-success">
        <p style="margin-top:10px;">Running task for 
          <?php foreach ($state['query']['post_type'] as $post_type) : ?>
            <span style="font-size: 0.95em; display:inline-block; background: #e1e1e1; color:#DC143C; font-family: monospace; padding: 1px 12px 2px; border-radius:20px; margin-left:5px;"><?= $post_type ?></span>
          <?php endforeach; ?>
        </p>
        <p style="margin-top:17px;"><strong><?=$state['total_posts_parsed']?> posts parsed of <?=$state['total_posts']?></strong> | <?=$state['total_intervals_parsed']?> intervals
        </p>
        <?php self::print_progress_bar($state); ?>

        <div style="display:block; border-radius: 3px; max-height:120px; overflow-y:scroll; border:1px solid darkgrey; background:#e1e1e1; padding: 8px 15px;">
          <?php
          foreach($state as $key => $property):?>
            <pre style="display:inline; color:#1E90FF;"><?=$key?></pre><pre style="display:inline; color:darkgrey;">: </pre><pre style="display:inline; color:#DC143C;"><?= is_array($property) ? print_r($property) : preg_replace('/\s\s+/', ' ', $property) ?></pre><br>
          <?php endforeach;
          ?>
        </div>
        <p style="margin-bottom:0px;"><small><i>Updated <span id="bc-updated-time"><?=time()-$state['last_update'];?></span>s ago</i> (Reload to update progress)</small></p>
        <script>
          setInterval(function(){ document.getElementById('bc-updated-time').innerHTML = Math.floor(new Date().getTime() / 1000) - <?=$state['last_update']?> }, 10000);
        </script>
        <form style="margin-top:-17px; margin-bottom:10px;" method="post" action="<?=self::$form_action?>">
          <input name="stop" value="1" type="hidden"/>
          <?php submit_button('Stop', 'delete'); ?>
        </form>
    </div>
    <?php
  }

  /*
  *
  *  UI Task Active Progress bar
  *
  */

  static function print_progress_bar($state) {
      $total = $state['total_posts_with_offset'];
      $start = 100/$total * $state['initial_offset'];
      $end = 100/$total * ($state['initial_offset'] + $state['total_posts_parsed']);
      $length = $end - $start;
      $scope = 100 - $start;
    ?>
    <div style="position:relative; overflow:hidden; width: 100%; max-width: none; height: 8px; background: #e1e1e1; border:1px solid darkgrey; border-radius: 5px; margin-top:7px; margin-bottom:20px; margin-top:10px;">
        <div style="width: <?=$scope?>%; 
          right:0; 
          position:absolute;
          background-color: white;
          background-image: repeating-linear-gradient(45deg, #87CEFA, #87CEFA 8px, #9dd7fb 8px, #9dd7fb 16px);
          top:0; height:100%;
          border-left:1px solid darkgrey;
          opacity:0.7;
          "> </div>
        <div style="width: <?=$length?>%; left:<?=$start?>%; 
          background:#46b450; 
          position:absolute;
          top:0; 
          height:100%;"></div>
    </div>
    <?php
  }
}