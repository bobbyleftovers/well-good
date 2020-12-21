(function ( $ ) {
  var throttle = require("@modules/main/throttle/throttle"),
      imglCount = $('*[data-module-init="imagelinks"]').length;

  $(window).on('scroll', throttle( function() { ImageLinks.init() }, 1000 ));

  module.exports = ImageLinks;

  /**
   * Static array containing all instances of ImageLinks
   * @type {Array}
   */
  ImageLinks.instances = [];

  /**
   * Gather and assign DOM elements
   * @param {HTMLElement} el Wrapper around [imagelinks] shortcode
   * @constructor
   */
  function ImageLinks( el ) {
    var self = this;
    this.delay = 0;
    this.$el = $(el);
    this.hotspotsVisible = false;

    ImageLinks.instances.push(this);
  }

  /**
   * Reveal the hotspots for each ImageLinks instance
   * Turn off scroll listener when all are visible
   * NOTE: kept this out of .prototype because it is not per instance functionality
   */
  ImageLinks.init = function() {
    for ( var i = 0; i < ImageLinks.instances.length; i++ ) {
      if (!ImageLinks.instances[i].hotspotsVisible) {
        if( ImageLinks.instances[i].$el.find('.imgl-view').length ) {
          ImageLinks.instances[i].showHotspotsWhenVisible();
        }
      }
    }
  }

  /**
   * Add class when element is in viewport and turn off the scroll listener
   */
  ImageLinks.prototype.showHotspotsWhenVisible = function() {

    this.$hotspots = this.$el.find('.imgl-hotspot');
    this.imageHeight = this.$el.find('img[id^=imgl-]').height();

    if( this.$el.isInViewport() ) {
      this.$el.addClass('hotspots-visible');
      this.addHotspotClickEvents();
      this.hotspotsVisible = true;
    }

  }

  /**
   * WIP Hotspot on click
   */
  ImageLinks.prototype.addHotspotClickEvents = function() {
    var self = this;

    this.$hotspots.each( function(index, el) {

      $(el).on('click', function() {
        $('body').addClass('js-popover-open');
        var activePopoverID = '#' + $('.imgl-popover.imgl-active').attr('id');
        var $activePopover = $(activePopoverID);
        var hotSpotTopPos = parseInt($(el).css('top'), 10);
        var hotSpotHeight = parseInt($(el).outerHeight(), 10);
        var popOverHeight = $activePopover.outerHeight();
        var total = self.imageHeight - (popOverHeight + hotSpotTopPos + hotSpotHeight/2);

        $activePopover.on('touchmove', function(e) {
          e.preventDefault();
        }, false);

        $('.imgl-popover').css('transform', 'translateY(0px)');
        $($activePopover).css('transform', 'translateY(-' + total + 'px)');

        $activePopover.find('.imgl-close').on('click', function() {
          $activePopover.css({ 'transform': 'translateY(0px)' });
        });

      });


    });



  };

  /**
   * Check if element is in viewport
   * @return {Boolean}
   * @link https://medium.com/talk-like/detecting-if-an-element-is-in-the-viewport-jquery-a6a4405a3ea2
   */
  $.fn.isInViewport = function() {

    var elementTop = $(this).offset().top + 200; // Adding 200 functions as a delay
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };

})(jQuery);





