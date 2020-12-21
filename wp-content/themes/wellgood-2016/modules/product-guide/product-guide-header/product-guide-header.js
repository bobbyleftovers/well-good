var $ = require('jquery');
var d = document;
var w = window;
var throttle = require( "@modules/main/throttle/throttle" );
var HEADER_CONTAINTER = $('.product-guide-header');

var ProductGuideHeader = function(el) {
  var self = this;

  self.$el = $(el);
  self.body = d.querySelectorAll('body')[0];
  self.$wrapper = $('.main-wrapper');
  self.$hero = $('.product-guide-hero');
  self.isIndex = self.$el.hasClass('product-guide-header--index');
  self.$scrollTrigger = $('.product-guide-hero__intro--arrow');
  self.scrollWrapper = document.querySelectorAll('.scroll-wrapper')[0];
  this.scrollDistance = 0
  self.usingDelta = false;
  self.horizontalRunway = (self.scrollWrapper.offsetWidth - w.innerWidth) * -1

  self.setHeight();
  self.events();
  window.onresize = function(event) {
    self.setHeight();
    self.events();
  }
  self.loaded();
}

ProductGuideHeader.prototype = {
  /**
   * Enable or disable header scrolling based on device width
   */
  events: function() {
    self = this
    this.getOrientation();
    this.scrolling();
    this.calculateOffset();

    this.$scrollTrigger.on('click', this.autoScroll.bind(this));

    if (this.orientation === 'landscape') {
      if (d.addEventListener) {
        this.body.addEventListener("mousewheel", this.mouseWheelHandler.bind(self));
        this.body.addEventListener("DOMMouseScroll", this.mouseWheelHandler.bind(self));
      }
      w.addEventListener('scroll', this.scrollYDistance.bind(this));
    } else {
      w.addEventListener('scroll', this.scrolling.bind(this));
    }
  },

  /**
   * Track distance of scrolling
   */
  scrollYDistance: function() {
    var diverged = this.usingDelta && Math.abs((w.pageYOffset * -1) - this.scrollDistance) > 100

    if (diverged) {
      w.scrollTo(0, this.scrollDistance * -1)
      this.usingDelta = false
    }
    this.scrollDistance = w.pageYOffset * -1
    this.scrolling()
  },

  /**
   * Convert horizontal scrolls to vertical
   */
  mouseWheelHandler: function() {
    var self = this
    var e = w.event || e;
    var delta = Math.max(-1, Math.min(1, (e.wheelDeltaX || -e.detail)));

    self.scrollDistance = self.scrollDistance + e.wheelDeltaX
    if (self.scrollDistance > 0) {
      self.scrollDistance = 0
    } else if (self.scrollDistance < self.horizontalRunway) {
      self.scrollDistance = self.horizontalRunway
    }

    if (delta !== 0 && self.scrollDistance <= 0 && self.scrollDistance > (self.scrollWrapper.offsetWidth * -1)) {
      self.usingDelta = true
      self.scrolling()
    }
    return false;

  },

  /**
   * Set a button to scroll past the header
   */
  autoScroll: function(){
    var offset = this.offset + 1
    var page = $('body,html')
    page.animate({ scrollTop: offset }, 650)
  },

  /**
   * Calculate the height of body according to the width of the scroll container
   */
  setHeight: function() {
    var featureWidth = this.scrollWrapper.scrollWidth - w.innerWidth + w.innerHeight
    this.body.style.height = featureWidth + "px"
  },

  /**
   * Remove loading class that disables css transitions
   */
  loaded: function() {
    var waitingEl = this.$el

    $(w).load(function() {
      waitingEl.removeClass("loading");
    });
  },

  /**
   * Slide down the header bar if it's past the tall header offset
   */
  scrolling: function(e) {
    var portrait = this.orientation !== 'landscape';
    var scrolled = this.$el.hasClass( "active" );
    var leftScroll = Math.abs(this.scrollDistance) > this.offset;
    var topScroll = $(w).scrollTop() > this.offset;

    var landscapeOpen    = !portrait &&  leftScroll && !scrolled;
    var portraitOpen     =  portrait &&  topScroll  && !scrolled;
    var landscapeClose   = this.isIndex ? !portrait && !leftScroll && scrolled : !portrait && !this.$wrapper.scrollLeft() && scrolled;
    var portraitClose    = this.isIndex ?  portrait && !topScroll  && scrolled :  portrait && !$(w).scrollTop() && scrolled;

    if (!portrait) {
      this.scrollWrapper.style.transform = "translateX(" + this.scrollDistance + "px)"
    } else {
      this.scrollWrapper.style.transform = "translateX(0px)"
    }
    if (portraitOpen || landscapeOpen) {
      this.openNav();
    } else if (portraitClose || landscapeClose) {
      this.closeNav();
    }
  },

  /**
   * Enable or disable header scrolling based on device width
   */
  getOrientation: function() {
    if(w.innerHeight > w.innerWidth){
      this.orientation = 'portrait';
    } else {
      this.orientation = 'landscape';
    }
  },

  /**
   * Calculate scroll offset based on current header height
   */
  calculateOffset: function() {
    if (this.orientation === 'landscape') {
      this.offset = this.$hero.outerWidth() - this.$el.width();
    } else {
      this.offset = this.$hero.outerHeight() - this.$el.height();
    }
  },

  /**
   * Control the opening of the nav
   */
  openNav: function() {
    this.$el.addClass( "active" );
  },

  /**
   * Control the closing of the nav and its speed
   */
  closeNav: function() {
    this.$el.removeClass( "active" );
  }
}

module.exports = ProductGuideHeader;
