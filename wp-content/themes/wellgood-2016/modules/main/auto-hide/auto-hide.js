(function ( $ ) {
  var throttle   = require( "@modules/main/throttle/throttle" ),
      debounce   = require( "@modules/main/debounce/debounce" ),
      TIMEOUT    = 2000,
      BREAKPOINT = 769;

  function AutoHide( el ) {
    this.$el     = $( el );
    this.timeout = null;
    this.$window = $( window );
    this.enabled = false;
    this.$hideAt = $( this.$el.data( "hide-at" ) );
    this.hideAtOffset = null;

    this.responsiveHandler();
    this.$window.on( "resize", debounce( $.proxy( this.responsiveHandler, this ) ) );
    this.$window.on( "load", $.proxy( this.calculateHideOffset, this ) );
  }

  AutoHide.prototype.calculateHideOffset = function() {
    if(this.$hideAt.length <= 0) return;
    this.hideAtOffset = this.$hideAt.offset().top + this.$hideAt.height() - this.$window.height();
  };

  AutoHide.prototype.responsiveHandler = function () {
    var width = this.$window.width();

    this.calculateHideOffset();

    if ( width >= BREAKPOINT && this.enabled ) {
      this.enabled = false;
      this.show();
      this.$window.off( "scroll.autohide click.autohide" );
    } else if ( width < BREAKPOINT && !this.enabled ) {
      this.enabled = true;
      this.delayHide();
      this.$window.on( "scroll.autohide click.autohide", throttle( $.proxy( this.scrollHandler, this ) ) );
    }
  };

  AutoHide.prototype.scrollHandler = function () {
    if ( !this.timeout ) {
      this.show();
    }

    if ( this.$window.scrollTop() >= this.hideAtOffset ) {
      this.hide();
    } else {
      this.delayHide();
    }
  };

  AutoHide.prototype.hide = function () {
    if ( this.enabled ) {
      this.$el.addClass( "js-autohide" );
    }
  };

  AutoHide.prototype.delayHide = debounce( AutoHide.prototype.hide, TIMEOUT );

  AutoHide.prototype.show = function () {
    this.$el.removeClass( "js-autohide" );
  };

  module.exports = AutoHide;
})( jQuery );
