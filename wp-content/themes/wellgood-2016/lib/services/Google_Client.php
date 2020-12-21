<?php

namespace WG\Services;

class Google_Client {

  private $client;

  /*
  *
  *  Constructor
  *
  */
  function __construct($auth = true){
    $this->client = new \Google_Client();
    $this->auth_service_account();
  }

  /** 
  *
  *  Auth Config
  *
  */

  public function load_auth_config(){
    $this->client->setAuthConfig(get_template_directory().'/google-client-auth.json');
  }


  /** 
  *
  *  OAuth
  *
  */

  public function auth_service_account(){
    putenv('GOOGLE_APPLICATION_CREDENTIALS='.get_template_directory().'/google-service-auth.json');
    $this->client->useApplicationDefaultCredentials();
  }


  /*
  *
  *  Get youtube
  *
  */

  public function get_youtube_service(){
    $this->client->addScope(\Google_Service_YouTube::YOUTUBE_READONLY);
    return new \Google_Service_YouTube($this->client);
  }
}
