var $ = require( 'jquery' );
var throttle = require('@modules/main/throttle/throttle')
var loadImage = require('@modules/main/image-load/image-load')

var loadAllImages = function($el){
  var images = $el.find('*[data-image-bg]');
  images.each(function(){
    loadImage(this)
  })
  $el.data('waypoint-images-loaded', true)
  return images;
}


var waypoint = function(elem) {

    var $el = $(elem);
    var padding = parseInt($el.data('padding')) | 0
    
    $el.data('waypoint-triggered', false)
    $el.data('waypoint-images-loaded', false)

    var removeWaypointClass = function() {

      if($el.data('waypoint-triggered') && $el.data('waypoint-images-loaded')) return;

      var elOffset = $el.offset().top
      var threshold = elOffset + padding - window.innerHeight

      var isIE = /MSIE \d|Trident.*rv:/.test(navigator.userAgent);

      if(isIE || !$el.data('waypoint-images-loaded')){
        if (isIE || window.pageYOffset > (threshold - 350)) {
          loadAllImages($el);
        }
      }
      
      if (isIE || (window.pageYOffset > threshold && ($el.hasClass('waypoint') || $el.hasClass('not-waypoint')))) {
        $el.removeClass('waypoint')
        $el.removeClass('not-waypoint')
        $el.data('waypoint-triggered', true)
      }
    }

    $(window).on('scroll', throttle(function() {
      removeWaypointClass()
    }, 100))

    removeWaypointClass();
}

var initWaypoints = function(el){
  $('body').addClass('trends-2020-page-loaded');
  var waypoints = $(el).find('.waypoint, .not-waypoint');
  if (waypoints.length) {
    for (var i = 0; i < waypoints.length; i++) {
      waypoint(waypoints[i])
    }
    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      setTimeout(function(){
        initWaypoints(el)
      }, 100);
    }
  }
}

module.exports = function (el) {
    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      loadImage(el);
      initWaypoints(el);
      var imagesInterval = setInterval(function() {
        var images = loadAllImages($(el));
        if(images.length === 0){
          clearInterval(imagesInterval)
        }
      }, 2000);
    }
    $(window).on('load', function(){
      setTimeout(function(){
        initWaypoints(el)
      }, 1)
    })
  };