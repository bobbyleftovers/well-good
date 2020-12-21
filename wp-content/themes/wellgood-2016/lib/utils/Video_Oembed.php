<?php

namespace WG\Utils;
use WG\Services\Youtube;

class Video_Oembed {

    protected $video = null;
    protected $src = null;

    function __construct($url){

      $this->src = $url;

      return $this;

    }

    function get_data() {

      if($this->video) return $this->video;

      $this->video = $this->parse_video_uri($this->src);

      $this->video['src'] = $this->src;

      if($this->video['provider'] === 'youtube'){
        $yt = new Youtube();
        $this->video['metadata'] =  $yt->get_video_data($this->src);
      }

      return $this->video;

    }

    function get_plyr_src() {

      $video_data = $this->get_data();

      $video_id = $video_data['id'];

      switch($video_data['provider']){
        case 'youtube':
          return "https://www.youtube.com/embed/$video_id?origin=".get_home_url()."&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1";
        break;
        case 'vimeo':
          return "https://player.vimeo.com/video/$video_id?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media";
        break;
      }
    }

    /* Parse the video uri/url to determine the video provider/source and the video id */
    function parse_video_uri( $url ) {

      // Parse the url
      $parse = parse_url( $url );

      // Set blank variables
      $video_provider = '';
      $video_id = '';

      // Url is http://youtu.be/xxxx
      if ( $parse['host'] == 'youtu.be' ) {

        $video_provider = 'youtube';

        $video_id = ltrim( $parse['path'],'/' );

      }

      // Url is http://www.youtube.com/watch?v=xxxx
      // or http://www.youtube.com/watch?feature=player_embedded&v=xxx
      // or http://www.youtube.com/embed/xxxx
      if ( ( $parse['host'] == 'youtube.com' ) || ( $parse['host'] == 'www.youtube.com' ) ) {

        $video_provider = 'youtube';

        parse_str( $parse['query'], $output );

        $video_id = $output['v'];

        if ( !empty( $feature ) )
          $video_id = end( explode( 'v=', $parse['query'] ) );

        if ( strpos( $parse['path'], 'embed' ) == 1 )
          $e = explode( '/', $parse['path'] );
          $video_id = end( $e );

      }

      // Url is http://www.vimeo.com
      if ( ( $parse['host'] == 'vimeo.com' ) || ( $parse['host'] == 'www.vimeo.com' ) ) {

        $video_provider = 'vimeo';

        $video_id = ltrim( $parse['path'],'/' );

      }
      $host_names = explode(".", $parse['host'] );
      $rebuild = ( ! empty( $host_names[1] ) ? $host_names[1] : '') . '.' . ( ! empty($host_names[2] ) ? $host_names[2] : '');
      // Url is an oembed url wistia.com
      if ( ( $rebuild == 'wistia.com' ) || ( $rebuild == 'wi.st.com' ) ) {

        $video_provider = 'wistia';

        if ( strpos( $parse['path'], 'medias' ) == 1 )
            $video_id = end( explode( '/', $parse['path'] ) );

      }

      // If recognised provider return video array
      if ( !empty( $video_provider ) ) {

        $video_array = array(
          'provider' => $video_provider,
          'id' => $video_id
        );

        return $video_array;

      } else {

        return false;

      }

    }
}
