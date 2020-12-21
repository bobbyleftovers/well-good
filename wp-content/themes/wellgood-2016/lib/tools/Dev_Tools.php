<?php

namespace WG\Tools;

class Dev_Tools {

  /**
	 *  Hooks
	 */

  function __construct(){

    add_action('admin_menu', array($this,'admin_menu'));

  }

  /*
  *  Add Tool Page
  */

  function admin_menu(){
    if(current_user_can('administrator')) add_management_page( 'Dev Tools', 'Dev Tools', 'manage_options', 'dev-tools', array( $this, 'admin_page' ) );
  }

  /*
  *  Admin Page
  */
  
  static function admin_page() {
   ?>
   <div id="poststuff" class="wrap dev-tools">

      <!-- ENV FUNCTIONS ---->
      <div class="postbox ">
        <h2 class="hndle">
          Env checks
        </h2>
        <div class="inside">
          <div class="main dev-tools__inside" style="overflow: hidden;">
            <div class="dev-tools__row">
              <div class="dev-tools__col">is_production()</div>
              <div class="dev-tools__col"><?= $this->true_false_label(is_production()) ?></div>
            </div>
            <div class="dev-tools__row">
              <div class="dev-tools__col">is_staging()</div>
              <div class="dev-tools__col"><?= $this->true_false_label(is_staging()) ?></div>
            </div>
            <div class="dev-tools__row">
            <div class="dev-tools__col">is_local()</div>
            <div class="dev-tools__col"><?= $this->true_false_label(is_local()) ?></div>
            </div>
            <div class="dev-tools__row">
              <div class="dev-tools__col">is_dev()</div> 
              <div class="dev-tools__col"><?= $this->true_false_label(is_dev()) ?></div>
            </div>
            <div class="dev-tools__row">
              <div class="dev-tools__col">get_host()</div> 
              <div class="dev-tools__col">
                <?= $this->label(get_host(), false) ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SERVER VARS ---->
      <div class="postbox ">
        <h2 class="hndle">
          Server vars
        </h2>
        <div class="inside">
          <div class="main" style="overflow: hidden;">
            <div class="dev-tools__row dev-tools__inside">
              <div class="dev-tools__col"><h3>$_ENV</h3></div>
              <div class="dev-tools__col dev-tools__pre">
                <pre><?=  var_dump($_ENV); ?></pre>
              </div>
            </div>

            <div class="dev-tools__row dev-tools__inside">
              <div class="dev-tools__col"><h3>$_SERVER</h3></div>
              <div class="dev-tools__col dev-tools__pre">
                <pre><?=  var_dump($_SERVER); ?></pre>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- THEME GLOBALS ---->
      <div class="postbox ">
        <h2 class="hndle">
          Theme globals
        </h2>
        <div class="inside">
          <div class="main" style="overflow: hidden;">
            <div class="dev-tools__row dev-tools__inside">
              <div class="dev-tools__col dev-tools__pre">
                <?php
                  $GLOB = \WG\Settings\Theme::get_theme_globals() ?? array();
                  $GLOB['REST_NAMESPACE'] = REST_NAMESPACE;
                  $GLOB['REST_VERSION'] = REST_VERSION;
                ?>
                <pre><?=  var_dump($GLOB); ?></pre>
              </div>
            </div>
          </div>
        </div>
      </div>
      

      <!-- PHP INFO ---->
      <div class="postbox ">
        <h2 class="hndle">
          PHP Info
        </h2>
        <div class="inside ">
          <div class="main dev-tools__inside" style="padding-left:0; padding-right:0;">
            <div class="main dev-tools__pre dev-tools__pre--padding " style="max-height: 600px;">
              <?php 
                ob_start();
                phpinfo();
                $pinfo = ob_get_contents();
                ob_end_clean();
                
                $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
                echo $pinfo;
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <style>
    .true {
      color:#00FF7F !important;
    }
    .false {
      color:#FF6347 !important;
    }
    .dev-tools__row {
      margin-bottom: 5px;
      display:flex;
    }
    .dev-tools__col {
      min-width:120px;
    }
    .dev-tools__pre {
      max-height: 300px;
      overflow: auto;
      background: #23282d;
      color: white;
      padding: 0px 15px;
      border-radius: 5px;
      font-family:monospace;
    }
    .dev-tools__pre * {
      color:white;
      font-family:monospace;
    }
    .dev-tools__pre pre {
      padding:0;
    }
    .dev-tools__pre--padding {
      padding-top: 15px;
      padding-bottom: 15px;
    }
    .dev-tools__inside {
      margin-bottom:25px;
      padding-right: 20px;
      padding-left: 20px;
    }
    .dev-tools__inside:first-child {
      margin-top:20px;
    }
    .dev-tools__inside h3 {
      margin-top: 5px;
    }
   </style>
   <?php
  }

  function true_false_label($val){
    $this->label(($val ? '<span class="true">true</span>': '<span class="false">false</span>'));
  }

  function label($val, $strong = true){
    $tag = $strong ? 'strong' : 'span';
    echo '<'.$tag.' class="dev-tools__pre" style="line-height: 1.7em; min-width: 60px; padding-top: 2px; padding-bottom: 2px; display:inline-block; text-align:center;">';
    echo $val;
    echo '</'.$tag.'>';
  }
 
 
}