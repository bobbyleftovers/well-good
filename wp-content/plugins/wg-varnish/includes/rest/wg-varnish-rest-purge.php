<?php


class WG_Varnish_REST_Purge extends WG_Varnish_REST {

   /**
	 * Endpoints - array of routes
	 *
	 * @since    1.0.2
	 * @access   protected
	 * @var      array
	 */

  protected $routes = array(
      array(
        'route' =>'/purge-urls',
        'callback' => 'purge_urls',
        'methods' => array('GET', 'POST')
      )
  );

  /**
	 * Callback
	 *
	 * @since    1.0.2
	 * @author   Barrel
	 */
  static function purge_urls( $request ) {

    $urls = (array) $request['urls'];

    $purge = $this->loader->load('Purge/WG_Varnish_Purge');

    return $purge->purge_urls($urls, true, false, true);
  }

}