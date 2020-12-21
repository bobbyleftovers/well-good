(function ( $ ) {
  module.exports = function( el ) {

    var $el = $(el);
    var $close = $( $el.data( 'close-elem' ) );
    var $open = $( $el.data( 'open-elem' ) );
    var $app = $( $el.data( 'app-elem' ) );

    function loadScripts() {
      // Mapbox styles
      $('<link>')
        .appendTo('head')
        .attr({
          type: 'text/css',
          rel: 'stylesheet',
          href: 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.38.0/mapbox-gl.css'
        });

      // Mapbox scripts
      loadScript(
        'https://api.tiles.mapbox.com/mapbox-gl-js/v0.38.0/mapbox-gl.js',
        function() {

          // Location hub scripts
          loadScript(locationHub.url);
        }
      );

    }

    function loadScript(script, callback) {
      $.ajax({
        url: script,
        dataType: "script",
        cache: true
      }).done(callback);
    }

    function locationHubInit(city) {
      // Empty app container and initialize location hub with city
      if( window.locationHubInit ) {
        // Initialize location script for this city
        window.locationHubInit($app, city);
      } else {
        // Wait for dependencies to finish loading, then initialize location script
        setTimeout(function() {
          locationHubInit(city);
        }, 200);
      }
    }

    $open.on('click', function(e){
      const city = $(this).data( 'city' );

      // Track event in GTM
      if ( dataLayer ) {
        dataLayer.push({
          'event': 'VirtualPageview',
          'virtualPageURL': window.location.pathname + 'map',
          'virtualPageTitle': 'Map | ' + document.title
        });
      }

      if( city ) {
        e.preventDefault();

        $('html,body').addClass('js-modal-maps-open');

        locationHubInit(city);

        $el.fadeIn('fast', function() {
          $el.find('.modal-maps-content__wrapper').scrollTop(0);
        });
      }
    });

    $close.on('click', function(e){
      e.preventDefault();
      $('html,body').removeClass('js-modal-maps-open');
      $el.fadeOut();
    });

    // Dynamically include map app and dependency scripts
    // Only init dependencies if this page has an open map link, and wait 5 seconds
    if ( $open.length ) {
      setTimeout(function() {
        loadScripts();
      }, 5000);
    }

  };
})( jQuery );
