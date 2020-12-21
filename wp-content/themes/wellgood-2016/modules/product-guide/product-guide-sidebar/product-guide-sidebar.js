var $ = require('jquery');
/**
 * Initializes the site sidebar.
 * @constructor
 */
function ProductGuideSidebar(el) {
  this.$el = $(el);
  this.$body = $('body');
  this.$window = $(window);
  this.$openButton = $('.product-guide-header__top--hamburger, .product-guide-hero__mobile--hamburger');
  this.$closeButton = $('.product-guide-sidebar__close, .product-guide-sidebar__mask');
  this.number = 20;
  this.animationSpeed = (parseFloat(this.$el.css('animation-duration')) * 1000) - 5;

  this.events();
  this.loaded();
}

ProductGuideSidebar.prototype = {
  events: function() {
    var self = this;

    self.$openButton.on('click', $.proxy(self.openActions, self));
    self.$closeButton.on('click', $.proxy(self.closeActions, self));
    $(document).keyup(function(e) {
        if (e.keyCode === 27) self.closeActions();
    });
  },

  /**
   * Remove loading class that disables css transitions
   */
  loaded: function() {
    var self = this;

    self.$window.load(function() {
      self.$el.removeClass("loading");
    });
  },

  openActions: function() {
    var self = this;

    self.$el.addClass('active');
    self.$body.addClass('fixed');
  },

  closeActions: function() {
    var self = this;

    self.$el.removeClass('active');
    self.$body.removeClass('fixed');
  }
}

module.exports = ProductGuideSidebar
