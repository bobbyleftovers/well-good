<?php
  if ( ! defined( 'ABSPATH' ) ) exit;

  function extract_url_param($desired, $parameters) {
    $temp = array();
    preg_match('/'. $desired . '=([^&#]*)/', $parameters, $temp);
    return (explode('=', $temp[0], 2)[1]);
  }

  $current_url = $_SERVER['REQUEST_URI']; 

  $current_url_params = array();
  preg_match('/\?(.*)/', $current_url, $current_url_params);

  $campaign_id = extract_url_param('campaign_id', $current_url_params[0]);
  $title = extract_url_param('title', $current_url_params[0]);
?>

<html>
  <head>
    <title>
      <?php echo $title ? $title : get_bloginfo('name') ?>
    </title>
  </head>
  <body>
    <style>
      html, body{
        margin:0px !important; 
        overflow: hidden;
      }

      iframe {
        width: 100%; 
        height: 100%; 
        border: none;
      }
    </style>

    <iframe src="<?php echo 'http://giveaways.dojomojo.ninja/landing/hosting-embed/' . $campaign_id . $current_url_params[0] ?>"></iframe>
  </body>
</html>