(function ( $ ) {
  module.exports = function( el ) {
    // Delete previous cookie
    if (Cookies.get('SESSwellgoodsignup')) {
      Cookies.remove('SESSwellgoodsignup');
    }

    var $el = $(el),
        $close = $( $el.data( 'close-elem' ) ),
        open_delay = $el.data( 'open-delay-seconds' ),
        iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream,
        iOSTop = 0,
        $input = $el.find('input');

    if ( ! open_delay ) {
      open_delay = 0;
    }

    if (iOS && $el.hasClass('js-modal-active')) {
        // on touchstart, make the bar absolute to prevent scrolling, set the top position so that
        // the field is always visible
        $input.on('touchstart', function(e) {
            iOSTop = $el.offset().top;
            $el.css({
                'position': 'absolute',
                'top': iOSTop,
                'bottom': 'auto'
            });
        });

        // prevent normal touchend otherwise links behind the original input position will trigger
        $input.on('touchend', function(e) {
            e.preventDefault();
            $(this).focus();
        });

        // scroll the viewport top edge to match with the CTA top edge
        // this is to ensure the whole CTA is visible
        $input.on('focus', function(e) {
            setTimeout(function() {
                $('html, body').animate({
                    'scrollTop': $el.offset().top
                }, 300);
            }, 500);
        });

        // restore the CTA when blurred
        $input.on('blur', function() {
            $el.css({
                'position': '',
                'top': '',
                'bottom': ''
            })
        });
    }

    if (!Cookies.get('__SESSwellgoodsignup')) {
      setTimeout(function() {
          $el.addClass('js-modal-active');
      }, open_delay * 1000);
      Cookies.set('__SESSwellgoodsignup', true, { expires: 365 });
    }
    $close.on('click', function(e){
      e.preventDefault();
      $el.removeClass('js-modal-active');
    });
  };
})( jQuery );