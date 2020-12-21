var $ = require('jquery');
var d = document;

/**
 * Initializes the site grid.
 * @constructor
 * @param {Object} el - The site grid container.
 */
function ProductGuideGrid(el) {
  this.$body = $('body');
  this.$document = $(d);
  this.$el = $(el);
  this.$item = $('.product-guide-grid__item');
  this.modal = d.querySelectorAll('.product-guide-details')[0];
  this.detailsOpen = false;
  this.scrollWrapper = d.querySelectorAll('.scroll-wrapper')[0];

  this.events();
}

ProductGuideGrid.prototype = {
  events: function() {
    var self = this;
    var launch = self.$item.find('.product-guide-grid__card--details, .product-guide-grid__card--image');
    launch.on('click', function(e){
      var item = $(this).closest('.product-guide-grid__item');
      self.openDetails(item);
    });
    self.$document.keyup(function(e) {
      if (!self.detailsOpen) return;
      if (e.keyCode === 27) self.closeDetails();
    });
  },

  checkModalBody: function(text) {
    var length = text.innerHTML.length;
    if (length > 400) {
      return true
    } else {
      return false
    }
  },

  getDetails: function(item) {
    var details = {}
    var article = item[0].getElementsByTagName('article')[0]
    var longDescription = this.checkModalBody(article.querySelector('.description'));

    details.imageSrc = article.querySelector('img').src
    details.title = article.querySelector('.title').innerHTML
    details.description = article.querySelector('.description').innerHTML
    details.price = article.querySelector('.price').innerHTML
    details.linkTitle = article.querySelector('.link').innerHTML
    details.linkUrl = article.querySelector('.link').href
    details.longDescription = longDescription ? true : false

    return details
  },

  applyDetails: function(details) {
    var info = this.modal.querySelector('.product-guide-details__info')
    var image = this.modal.querySelector('.product-guide-details__image')
    var title = this.modal.querySelector('.product-guide-details__title')
    var description = this.modal.querySelector('.product-guide-details__description')
    var price = this.modal.querySelector('.product-guide-details__price')
    var link = this.modal.querySelector('.product-guide-details__link')

    image.style.backgroundImage = "url('" + details.imageSrc + "')";
    title.innerHTML = details.title
    description.innerHTML = details.description
    price.innerHTML = details.price
    link.innerHTML = details.linkTitle
    link.href = details.linkUrl

    if (details.longDescription) {
      info.classList.add('product-guide-details__info--long')
    }
  },

  resetDetails: function() {
    var container = this.modal.querySelector('.product-guide-details__info-container')
    var info = this.modal.querySelector('.product-guide-details__info')

    container.scrollTop = 0;
    info.classList.remove('product-guide-details__info--long')
  },

  openDetails: function(item) {
    var details = this.getDetails(item);

    this.applyDetails(details)

    this.modal.classList.add("details-open");
    item.addClass('details-open');
    this.detailsOpen = true;
    this.$body.addClass('fixed');
    this.maskClose = d.querySelectorAll('.product-guide-details__mask')[0];
    this.buttonClose = d.querySelectorAll('.product-guide-details__close')[0];
    this.maskClose.addEventListener('click', $.proxy( this.closeDetails, this ));
    this.buttonClose.addEventListener('click', $.proxy( this.closeDetails, this ));
  },

  closeDetails: function() {
    this.resetDetails();
    this.detailsOpen = false;
    this.modal.classList.remove("details-open");
    this.$body.removeClass('fixed');
    this.$item.removeClass('details-open');

  }
}

module.exports = ProductGuideGrid



