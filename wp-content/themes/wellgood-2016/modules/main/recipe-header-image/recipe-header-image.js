/**
 * Lazy load the recipe header video.
 * @constructor
 * @param {Object} el - The video element.
 */

var $ = jQuery;

function RecipeHeaderVideo(el) {
  if( $(window).width() >= 768 ) {
    $(window).on('load', function() {
      var $source = $(el).find('source');
      var new_src = $source.attr('data-src');
      $source.attr('src', new_src);
      el.load();
      el.play();
    });
  }
}

module.exports = RecipeHeaderVideo;
