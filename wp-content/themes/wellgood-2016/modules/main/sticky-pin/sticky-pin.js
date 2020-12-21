(function ( $ ) {
  var STICKY_PIN_BREAKPOINT = 1024;
  var debounce = require( "@modules/main/debounce/debounce" );

  module.exports = StickyPin;

  /**
   * Static array containing all instances of Sticky Pin
   * @type {Array}
   */
  StickyPin.instances = [];

  /**
   * Initialize sticky / pin effect on element
   * @param {HTMLElement} el
   * @constructor
   */
  function StickyPin( el ) {
    this.$window   = $( window );
    this.$el       = $( el );
    this.id = Math.random();

    /**
     * Element will become sticky in relative to this element
     * @type {jQuery}
     */
    this.$pinAt    = $( this.$el.data( "pin-at" ) );

    /**
     * Element will flow normally in relative to this element
     * @type {jQuery}
     */
    this.$flowAt   = $( this.$el.data( "flow-at" ) );

    /**
     * Element will flow normally in relative to this element when in mobile responsive breakpoint
     * @type {jQuery}
     */
    this.$responsiveFlowAt   = $( this.$el.data( "responsive-flow-at" ) );

    /**
     * Optional breakpoint override
     * @type number
     */
    this.breakpoint   = this.$el.data( "breakpoint" ) ? this.$el.data( "breakpoint" ) : STICKY_PIN_BREAKPOINT;

    /**
     * Scroll position where pinning effect will be activated / deactivated
     * @type {null|float}
     */
    this.pinOffset = null;

    /**
     * Scroll position where flow effect will be activated / deactivated
     * @type {null|float}
     */
    this.flowOffset = null;
    this.responsiveFlowOffset = null;
    this.offsetTop = null;

    this.isPinned  = false;
    this.isFlowing = false;
    this.isResponsiveFlowing = false;

    this.responsiveHandler();
    this.$window.load( $.proxy( this.responsiveHandler, this ) );

    this.throttleDelay = 0;

    StickyPin.instances.push( this );
  }

  /**
   * Force-check the scrolling positions of each StickyPin instances.
   * @param recalculateOffset Set to `true` to force recalculate offsets
   */
  StickyPin.checkPositions = function( recalculateOffset ) {
    if ( typeof recalculateOffset === 'undefined' ) {
      recalculateOffset = false;
    }

    for ( var i = 0; i < StickyPin.instances.length; i++ ) {
      if ( recalculateOffset ) {
        StickyPin.instances[i].calculateOffset();
      }
      StickyPin.instances[i].checkScrollPosition();
    }
  };

  /**
   * Check current window scrolling position, and pin / reset / flow
   * element accordingly
   */
  StickyPin.prototype.checkScrollPosition = function () {
    var scrollTop = this.$window.scrollTop();
    var windowWidth = this.$window.width();

    if ( windowWidth >= this.breakpoint ) {

      var elTransform = this.$el.attr('top');

      if ( !this.isPinned && scrollTop > this.pinOffset && scrollTop < this.flowOffset && this.$flowAt.height() > this.$el.height() ) {
        if(this.$el.hasClass('post-ad-b-wrapper')){
          this.$el.addClass('is-sticking');
        }
        this.pin();
      }

      if ( !this.isFlowing && scrollTop > this.flowOffset && this.$flowAt.height() > this.$el.height() ) {
        this.flow();
      }

      if ( this.isPinned && scrollTop < this.pinOffset ) {
        if(this.$el.hasClass('post-ad-b-wrapper')){
          this.$el.removeClass('is-sticking');
        }
        this.reset();
      }
    } else if( this.$responsiveFlowAt.length > 0 ) {

      if( !this.isResponsiveFlowing && this.$el.offset().top > this.responsiveFlowOffset ) {
        this.responsiveFlow();
      }

      if ( this.isResponsiveFlowing && scrollTop < this.$el.offset().top-this.$window.height() ) {
        this.reset();
      }
    }
  };

  /**
   * Calculate screen position offsets that will start the pin / reset / flow effects
   */
  StickyPin.prototype.calculateOffsetStart = function () {
    this.reset();
    this.offsetTop = this.$el.offset().top;
  };

  /**
   * Calculate screen position offsets that will trigger the pin / reset / flow effects
   */
  StickyPin.prototype.calculateOffset = function () {
    var flowBottomOffset = this.$flowAt.offset().top + this.$flowAt.height();

    this.pinAtHeight = this.$pinAt.height();
    this.topEdge = this.pinAtHeight + parseFloat( this.$el.css( "margin-top" ) );

    this.pinOffset   = this.offsetTop - this.topEdge;
    this.flowOffset  = flowBottomOffset - this.$el.height() - this.topEdge;

    if(this.$responsiveFlowAt.length > 0) {
      var responsiveFlowBottomOffset = this.$responsiveFlowAt.offset().top + this.$responsiveFlowAt.height();
      this.responsiveFlowOffset  = responsiveFlowBottomOffset - this.$el.height() - this.topEdge;
    }
  };

  /**
   * Check current device / window size and disable or enable the effect accordingly
   */
  StickyPin.prototype.checkSize = function () {
    var t = this;
    t.$window.find('iframe').load( function(e) {
      setTimeout(function(){
        $.proxy( t.checkSize, t );
      }, 2500);
    } );
    var windowWidth = this.$window.width();
    if ( windowWidth >= this.breakpoint || this.$responsiveFlowAt.length > 0 ) {
      this.enable();
    } else {
      this.disable();
    }
  };


  /**
   * Enable the effect
   */
  StickyPin.prototype.enable = function() {
    this.reset();
    this.calculateOffset();
    this.checkScrollPosition();
    this.$window.on( "scroll.stickyPin-"+this.id, $.proxy( this.checkScrollPosition, this ) );
  };

  /**
   * Disable the effect
   */
  StickyPin.prototype.disable = function() {
    this.$window.off( "scroll.stickyPin-"+this.id );
    if ( this.isPinned || this.isFlowing ) {
      this.reset();
    }
  };

  /**
   * Check size and handle resize event
   */
  StickyPin.prototype.responsiveHandler = function () {
    var t = this;
    t.calculateOffsetStart();
    t.checkSize();
    t.$window.on( "resize.stickyPin-"+this.id+" DOMNodeInserted.stickyPin-"+this.id+" DOMNodeRemoved.stickyPin-"+this.id+" ajaxSuccess.stickyPin-"+this.id, debounce( $.proxy( t.checkSize, t ) ) );
  };

  /**
   * Activate "pinning"
   */
  StickyPin.prototype.pin = function () {
    this.$el.css({
      "position": "fixed",
      "left": this.$el.offset().left,
      "right": "",
      "top": this.pinAtHeight + "px",
      "bottom": ""
    });

    this.isPinned  = true;
    this.isFlowing = false;
    this.isResponsiveFlowing = false;
  };

  /**
   * Deactivate "pinning" and also "flowing"
   */
  StickyPin.prototype.reset = function () {
    this.$el.css({
      "position": "",
      "left": "",
      "right": "",
      "top": "",
      "bottom": ""
    });

    this.isPinned  = false;
    this.isFlowing = false;
    this.isResponsiveFlowing = false;
  };

  /**
   * Activate "flowing"
   */
  StickyPin.prototype.flow = function () {
    this.$el.css({
      "position": "absolute",
      "left" : "",
      "right": "",
      "top": "initial",
      "bottom": - parseFloat( this.$el.css( "margin-bottom" ) ) + "px"
    });

    this.isPinned  = false;
    this.isFlowing = true;
    this.isResponsiveFlowing = false;
  };

  /**
   * Activate "responsive flow"
   */
  StickyPin.prototype.responsiveFlow = function () {
    this.$el.css({
      "position": "absolute",
      "left": "",
      "right": "",
      "top": "",
      "bottom": ""
    });

    this.isPinned  = false;
    this.isFlowing = false;
    this.isResponsiveFlowing = true;
  };

})( jQuery );
