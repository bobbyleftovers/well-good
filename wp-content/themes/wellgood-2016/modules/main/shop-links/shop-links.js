(function($) {
  const debounce = require( '@modules/main/debounce/debounce' )
  const BREAKPOINT = 1080;
  const options = {
    setGallerySize: false,
    groupCells: false,
    prevNextButtons: false,
    pageDots: true,
    adaptiveHeight: true,
    cellAlign: 'left'
  }

  function hasFlickity(el) {
    return $(el).data('flickity') !== undefined
  }

  function isOverBreakpoint() {
    return window.innerWidth > BREAKPOINT
  }

  function init(el) {
    const slider = el.querySelector('.js-mobile-slider')

    if(!slider) {
      return
    }

    if(hasFlickity(slider) && isOverBreakpoint()) {
      $(slider).flickity('destroy')
    }

    if(!hasFlickity(slider) && !isOverBreakpoint()){
      $(slider).flickity(options)
    }
  }

  function shopLink(el) {
    const link = el.getElementsByClassName('shop-links__link');
    const content = el.getElementsByClassName('shop-links__content');
    const product = link[0].dataset.info;

    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.attributeName === "class") {
          var attributeValue = $(mutation.target).prop(mutation.attributeName);
          if (attributeValue.includes('is-selected') && dataLayer) {
            dataLayer.push({
              'event': 'shop link swipe',
              'product': product
            })
          }
        }
      });
    });
    observer.observe(content[0], {
      attributes: true
    });

    $(window).on('resize', debounce(function() {
      init(el)
    }, 300));

    setTimeout(function () {
      $(window).trigger('resize')
    }, 300);

    $(link).on( "mouseenter", function() {
      if (dataLayer && !$(this).hasClass('hovered') && window.innerWidth > 768) {
        $(this).addClass('hovered')
        dataLayer.push({
          'event': 'shop link hover',
          'product': product
        })
      }
    });
  }

  module.exports = shopLink
})(jQuery)

