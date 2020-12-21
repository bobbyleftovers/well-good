var $ = require('jquery');
/**
 * Initializes the site subnav.
 * @constructor
 * @param {Object} el - The site subnav element.
 */
function ProductGuideSubnav(el) {
  this.selector = document.getElementById('product-guide-page-select');
  this.$arrow = $(el).find('.product-guide-subnav__mobile-menu--arrow');

  this.selectorInit();
}

ProductGuideSubnav.prototype = {
  /**
   * Controls the redirect after the mobile nav item is selected
   */
  selectorInit: function() {
    self = this;
    self.selector.addEventListener('change', function () {
      document.getElementById("nav-display").innerHTML = $(this).find(':selected').attr('value');
      self.$arrow.addClass('loading');
    });
  }
}


module.exports = ProductGuideSubnav
