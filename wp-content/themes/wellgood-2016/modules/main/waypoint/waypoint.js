/**
 * Remove class 'waypoint' once it is in viewpoint
 * Add attribute data-padding="{{ integer }}" to element to offset threshold
 * Note that this currently will not work for elements that are position: absolute | fixed
 * @param {HTMLElement} elem
 * @link https://gitlab.com/barrel/wunder/blob/master/wp-content/themes/wunder/src/js/lib/dom.js
 */

(function($) {

  var throttle = require('@modules/main/throttle/throttle')

  module.exports = function(elem) {

    var $el = $(elem);
    var padding = parseInt($el.attr('data-padding')) | 0

    var removeWaypointClass = function() {
      var elOffset = $el.offset().top
      var threshold = elOffset + padding - window.innerHeight

      if (window.pageYOffset > threshold && $el.hasClass('waypoint')) {
        $el.removeClass('waypoint')
      }
    }

    $(window).on('scroll', throttle(function() {
      removeWaypointClass()
    }, 100))

    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
      removeWaypointClass();
    } else {
      $(window).on('load', removeWaypointClass())
    }
  }

})(jQuery);
