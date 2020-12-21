(function ( $ ) {
  /**
   * Initializes the pinterest button overlays.
   * @constructor
   * @param {Object} el - The site related posts element.
   */
  function Pinterest( el ) {
    var self = this;
    self.$el = $( el );
    self.$main = self.$el.find('.post__main');
    self.$imgs = self.$main.find('img');
    self.$imgs.each( function (i, el){
      if( !$(el).hasClass('no-pin') && !$(el).parents('.no-pin').length ) {
	      if ( !$(el).parents('.image-module').length ) {
          $(el).not('[id^=imgl]').wrap($('<div class="post__image-wrapper"/>'));
        } else {
	        $(el).not('[id^=imgl]').parents('.image-module').addClass('post__image-wrapper');
        }
        $(el).not('[id^=imgl]').parent().append( self.markup(el.src) );
      }
    });
  };

  Pinterest.prototype.markup = function (link){
    var markup = '<a target="_blank" href="//pinterest.com/pin/create/link/?url=' + encodeURIComponent(window.location.href) +
    '&amp;media='+ encodeURIComponent(link) +
    '" class="post__pin-link">' +
    '<span class="post__pin-wrapper">' +
    '<span class="post__pin-image social-share__button social-share__button--circle social-share__button--pinterest">' +
    '<span class="icon-pinterest-p"></span></span>' +
    '<span class="post__pin-label">Pin It</span></span></a>';
    return markup;
  }

  module.exports = Pinterest;
})( jQuery );
