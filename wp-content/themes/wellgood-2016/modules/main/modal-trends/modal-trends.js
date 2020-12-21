(function ( $ ) {
  module.exports = function( el ) {

    var $el = $(el);
    var $container = $( $el.data( 'container-elem' ) );
    var $prev = $( $el.data( 'prev-elem' ) );
    var $next = $( $el.data( 'next-elem' ) );
    var $close = $( $el.data( 'close-elem' ) );
    var trend_el = $el.data( 'article-elem' );
    var open_el = $el.data( 'open-elem' );
    var $open = $( open_el );
    var content_types = [
      { el: '.js-modal-trends__image', type: 'data-src' },
      { el: '.js-modal-trends__image--full', type: 'data-src' },
      { el: '.js-modal-trends__image__credit', type: 'html' },
      { el: '.js-modal-trends__sponsored-link', type: 'href' },
      { el: '.js-modal-trends__sponsor-link__by', type: 'html' },
      { el: '.js-modal-trends__sponsor-link__image', type: 'src' },
      { el: '.js-modal-trends__number', type: 'html' },
      { el: '.js-modal-trends__title', type: 'html' },
      { el: '.js-modal-trends__content', type: 'html' },
      { el: '.js-modal-trends__share__button--facebook', type: 'href' },
      { el: '.js-modal-trends__share__button--twitter', type: 'href' },
      { el: '.js-modal-trends__share__button--pinterest', type: 'href' },
      { el: '.js-modal-trends__share__button--email', type: 'href' },
    ];

    $open.on('click', function(e){



      var $trend = $(this).closest( trend_el );

      if( $trend.length ) {
        e.preventDefault();

        var dataEvent = this.getAttribute('data-vars-event')
        var dataInfo = this.getAttribute('data-vars-info')

        if (dataLayer) {
          dataLayer.push({
            'event': dataEvent,
            'info': dataInfo
          })
        }


        $('html,body').addClass('js-modal-trends-open');

        for( var i = 0; i < content_types.length; i++ ) {
          var content_el = content_types[i].el;
          var $content_el = $trend.find( content_el );

          if( content_types[i].type == 'html' ) {

            if( $content_el.length && $content_el.html().length ) {
              $el.find( content_el ).html( $content_el.html() );
            } else {
              $el.find( content_el ).html( '' );
            }

          } else if( content_types[i].type == 'data-src' ) {

            $el.find( content_el ).attr( 'src', '' );
            if( $content_el.length && $content_el.data('src') && $content_el.data('src').length ) {
              $el.find( content_el ).attr( 'src', $content_el.data('src') );
            }

          } else if( content_types[i].type == 'src' ) {

            $el.find( content_el ).attr( 'src', '' );
            if( $content_el.length && $content_el.attr('src') && $content_el.attr('src').length ) {
              $el.find( content_el ).attr( 'src', $content_el.attr('src') );
            }

          } else if( content_types[i].type == 'href' ) {

            if( $content_el.length && $content_el.attr('href') && $content_el.attr('href').length ) {
              $el.find( content_el ).attr( 'href', $content_el.attr('href') );
            } else {
              $el.find( content_el ).attr( 'href', '' );
            }

          }

        }

        var sponsored = false;

        $sponsored = $el.find( '.js-modal-trends__sponsored' );
        $sponsored_link = $el.find( '.js-modal-trends__sponsored-link' );
        $sponsored_link.removeClass('show');
        if( $sponsored_link.attr('href') && $sponsored_link.attr('href').length ) {
          $sponsored.removeClass('show');
          $sponsored_link.addClass('show');
        }

        $sponsored_image = $el.find( '.js-modal-trends__sponsor-link__image' );
        $sponsored_image.removeClass('show');
        if( $sponsored_image.attr('src') && $sponsored_image.attr('src').length ) {
          $sponsored_image.addClass('show');
          sponsored = true;
        }

        $sponsored_by = $el.find( '.js-modal-trends__sponsor-link__by' );
        $sponsored_by.removeClass('show');
        if( $sponsored_by.html().length ) {
          $sponsored_by.addClass('show');
          sponsored = true;
        }

        $sponsored.removeClass('show');
        if( sponsored && !$sponsored_link.hasClass('show') ) {
          $sponsored.addClass('show');
        }

        $el.fadeIn('fast', function() {
          $el.find('.modal-trends-content__wrapper').scrollTop(0);
        });
      }

      var $trends = $( trend_el );
      var trend_index = $trends.index( $trend );
      $container.data( 'trend-index', trend_index );

      if( trend_index > 0 ) {
        var $trend_prev = $trends.eq( trend_index - 1 );
        $prev.attr( 'href', $trend_prev.find( open_el ).attr('href') );
        $prev.removeClass('disabled');
      } else {
        $prev.attr( 'href', '' );
        $prev.addClass('disabled');
      }

      var $trend_next = $trends.filter(":gt(" + trend_index + ")").first();
      if( $trend_next.length ) {
        $next.attr( 'href', $trend_next.find( open_el ).attr('href') );
        $next.removeClass('disabled');
      } else {
        $next.attr( 'href', '' );
        $next.addClass('disabled');
      }

    });

    $prev.on('click', function(e){
      e.preventDefault();
      var $trends = $( trend_el );
      var trend_index = $container.data( 'trend-index' );
      var $trend_prev = $trends.eq( trend_index - 1 );
      if( trend_index > 0 && $trend_prev.length ) {
        $trend_prev.find( open_el ).trigger( 'click' );
      }
    });

    $next.on('click', function(e){
      e.preventDefault();
      var $trends = $( trend_el );
      var trend_index = $container.data( 'trend-index' );
      var $trend_next = $trends.filter(":gt(" + trend_index + ")").first();
      if( $trend_next.length ) {
        $trend_next.find( open_el ).trigger( 'click' );
      }
    });

    $close.on('click', function(e){
      e.preventDefault();
      $('html,body').removeClass('js-modal-trends-open');
      $el.fadeOut();
    });

  };
})( jQuery );
