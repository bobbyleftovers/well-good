var $ = require( 'jquery' );

module.exports = function (el) {
    var $el = $(el);
    var initSlider = function(){

      var flktyDesktop = new Flickity( $el.find('.trends-2020-game-changers__slideshow--desktop')[0], {
        cellAlign: 'center',
        groupCells: true,
        contain: true,
        pageDots: false,
        wrapAround: true
      });
      
      var flktyMobile = new Flickity( $el.find('.trends-2020-game-changers__slideshow--mobile')[0], {
        cellAlign: 'center',
        groupCells: false,
        contain: true,
        pageDots: false,
        wrapAround: true
      });

      if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
        $(window).on('load', function(){
          flktyDesktop.resize();
          flktyMobile.resize();
        })
        setTimeout(function(){
          flktyDesktop.resize();
          flktyMobile.resize();
        }, 10);
        setTimeout(function(){
          flktyDesktop.resize();
          flktyMobile.resize();
        }, 30);
        setTimeout(function(){
          flktyDesktop.resize();
          flktyMobile.resize();
        }, 60);
        flktyDesktop.resize();
        flktyMobile.resize();
      }
    }
    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      initSlider()
    } else {
      $(window).on('load', function(){
        initSlider()
      })
    }
}