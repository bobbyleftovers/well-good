var $ = require( 'jquery' );
var loadImage = require('@modules/main/image-load/image-load')

module.exports = function (el) {

    var timeout = null

    var maxOpacity = 0.2

    var screenDuration = 0.6

    // var $header = jQuery('.header')

    var onScroll = function(){

      clearTimeout(timeout)

      var scrollTop = document.documentElement.scrollTop | document.body.scrollTop

      // scrollTop = scrollTop + (scrollTop - $header.offset().top);

      var duration = window.innerHeight*screenDuration;

      if(scrollTop && scrollTop > duration){
        el.style.display = 'none'
        timeout = setTimeout(function(){
          el.style.opacity = 0
        }, 10)
      } else {
        el.style.display = 'block'
        var opacity = maxOpacity-(maxOpacity/duration*scrollTop)
        if(opacity > maxOpacity) opacity = maxOpacity
        el.style.opacity = opacity
      }
    }

    $(window).on('scroll', onScroll)

    $(window).on('load', function(){
      onScroll()
      setTimeout(function(){
        loadImage(el)
      },10)
    })

    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      loadImage(el)
      onScroll()
    }

}
