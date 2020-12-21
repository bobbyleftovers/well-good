<?php

namespace WG\Plugins;

class Page_Post_Redirection {

  function __construct(){
    add_filter( 'site_transient_update_plugins', array($this, 'prevent_update_notification') );
  }

  // Prevent update notification sfor Quick Page/Post Redirect Plugin
  function prevent_update_notification( $value ) {
    if ( isset( $value ) && is_object( $value ) && isset( $value->response[ 'quick-pagepost-redirect-plugin/page_post_redirect_plugin.php' ] ) ) {
      unset( $value->response[ 'quick-pagepost-redirect-plugin/page_post_redirect_plugin.php' ] );
    }

    return $value;
  }
}


