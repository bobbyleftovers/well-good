var $ = require('jquery');
var d = document;
var NAV_ITEMS = document.querySelectorAll('.product-guide-subnav__item');
var RECOMMENDATIONS_CONTAINER = $('.product-guide-quiz__recommendations');
var NAVIGATION_WARNING = $('.product-guide-quiz__navigation--warning');
var MODAL_TRANSITION = 333;

function ProductGuideQuiz(el) {
  this.$document = $(document);
  this.$body = $('body');
  this.$window = $(window);
  this.$el = $(el);
  this.modal = d.querySelectorAll('.product-guide-details')[0];
  this.$modalOpen = $('.modal__open--quiz');
  this.$modalClose = $('.modal__close--quiz');

  this.productData = {};
  this.currentUrl = window.location.href;
  this.$slides = this.$el.find('.product-guide-quiz__slide');
  this.$answers = this.$el.find('.product-guide-quiz__answers');
  this.$legend = this.$el.find('.product-guide-quiz__body--legend');
  this.$next = $('#next');
  this.$restart = $('#restart');
  this.$browse = $('.product-guide-quiz__back');
  this.$form = $(this.$el.find('form'))[0];
  this.recommendationsFound = false;
  this.recommendationsFetched = false;
  this.detailsOpen = false;
  this.pageID = this.getPageID();

  this.currentSlide = 0;
  this.updateLegend();
  this.updateSlide(this.currentSlide);

  this.events();
  this.loaded();
}

ProductGuideQuiz.prototype = {
  events: function() {
    var self = this;
    var resetTimer;

    this.$modalOpen.on('click', this.openQuiz.bind(this));
    this.$modalClose.on('click', this.closeQuiz.bind(this));
    self.$document.keyup(function(e) {
      if (self.detailsOpen) return;

      if (e.keyCode === 27) self.closeQuiz();
    });

    this.$restart.on('click', this.restartQuiz.bind(this));
    this.$next.on('click', this.changeSlide.bind(this, 'next'));

    // Remove focus
    this.$next.mousedown(function(e){ e.preventDefault(); });
    this.$restart.mousedown(function(e){ e.preventDefault(); });

    $(".product-guide-quiz__answer").on({
      click: function() {
        var answers = $(this).closest('.product-guide-quiz__answers');

        answers.addClass('product-guide-quiz__answers--selected');
        self.$next.addClass('active');
      },
      mouseleave: function() {
        var answers = $(this).closest('.product-guide-quiz__answers');

        resetTimer = setTimeout(function() {
          answers.removeClass('product-guide-quiz__answers--hovering');
        }, 250);
      },
      mouseover: function() {
        var answers = $(this).closest('.product-guide-quiz__answers');

        clearTimeout(resetTimer);
        answers.addClass('product-guide-quiz__answers--hovering');
      }
    });
  },

  loadRecommendations: function(id) {
    var self = this;
    // var serial = $(self.$form).serializeArray();
    var constraints = JSON.stringify(self.getConstraints());
    $.ajax({
      url: '/wp-json/wellandgood/v1/product-guide-quiz',
      type: 'POST',
      dataType: 'json',
      data: {
        post_id: id,
        constraints: constraints,
        // serial: JSON.stringify(serial)
      },
      beforeSend: function (xhr) {
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      }
    }).done(function(data) {
      self.recommendationsFetched = true;
      if (data || data !== null) {
        self.recommendationsFound = true;
      }
      self.updateRecommendations(data);
      self.recommendationEvents();
    }).error(function(error) {
      console.log(error);
    });
  },

  loaded: function() {
    var self = this;

    self.$window.load(function() {
      self.$el.removeClass("loading");
    });
  },

  openQuiz: function() {
    this.$body.addClass('fixed');
    this.$el.addClass('active');
  },

  closeQuiz: function() {
    var self = this;

    self.$body.removeClass('fixed');
    self.$el.removeClass('active');
    setTimeout(function() {
      self.restartQuiz();
    }, MODAL_TRANSITION);
  },

  addWarning: function() {
    var self = this;
    var type = self.$slides[self.currentSlide].getAttribute('data-type');
    var singleMessage = 'select one of the options below';
    var multipleMessage = 'select one or more of the options below';
    var message = type == 'single' ? singleMessage : multipleMessage;

    NAVIGATION_WARNING.html(message);
  },

  changeSlide: function(direction) {
    var lastSlide = this.$slides.length - 1;
    var selected = this.$next.hasClass('active');

    if (!selected) {
      return;
    }

    this.addWarning();

    if (direction === 'next') {
      this.currentSlide++;
    } else if (direction === 'prev') {
      this.currentSlide--;
    }
    if (this.currentSlide === lastSlide) {
      this.loadRecommendations(this.pageID);
    }
    this.updateSlide(this.currentSlide, direction);
  },

  updateSlide: function(slide, direction) {
    var previous = direction === 'next' ? slide - 1 : slide + 1;
    var $activate = $(this.$slides[slide]);
    var $deactivate = $(this.$slides[previous]);

    this.updateLegend();
    this.updateNavigation();
    this.addWarning();

    $deactivate.removeClass('product-guide-quiz__slide--active');
    $activate.addClass('product-guide-quiz__slide--active');

    this.$next.removeClass('active');
  },

  updateLegend: function() {
    var indexCurrent = this.currentSlide + 1;
    var indexTotal = this.$slides.length;
    var message = 'Question ' + indexCurrent + ' of ' + (indexTotal - 1);

    if (indexCurrent < indexTotal) {
      this.$legend.html(message);
    } else {
      if (this.recommendationsFetched) {
        if (this.recommendationsFound) {
          this.$legend.html('We\'ve found your gifts!');
        } else {
          this.$legend.html('<h3>We couldn\'t find any gifts, try again or browse all the gifts!</h3>');
        }
      } else {
        this.$legend.html('Searching for the perfect products for you...');
      }
    }
  },

  updateNavigation: function() {
    if (this.currentSlide === (this.$slides.length - 2)) {
      this.$next.html("Finish");

    } else if (this.currentSlide === (this.$slides.length - 1)) {
      this.$restart[0].style.display = 'flex';
      this.$next[0].style.display = 'none';
      this.$browse[0].style.display = 'flex';

    } else {
      this.$next[0].style.display = 'flex';
      this.$restart[0].style.display = 'none';
      this.$browse[0].style.display = 'none';

    }
  },

  getPageID: function() {
    for (var i = 0; i < NAV_ITEMS.length; i++) {
      var navItem = NAV_ITEMS[i].getElementsByTagName('a');
      if (navItem) {
        if (this.currentUrl == navItem[0].href) {
          var currentPageID = NAV_ITEMS[i].getAttribute('data-id');
          return currentPageID;
        }
      }
    }
    return false;
  },

  restartQuiz: function() {
    for (var i = 0; i < this.$slides.length; i++) {
      var $question = $(this.$slides[i]);
      $question.removeClass('product-guide-quiz__slide--active');
    }
    this.$answers.length
    for (i = 0; i < this.$answers.length; i++) {
      var $answer = $(this.$answers[i]);
      $answer.removeClass('product-guide-quiz__answers--selected');
    }

    this.productData = {};
    this.recommendationsFetched = false;
    this.recommendationsFound = false;
    this.$form.reset();

    this.currentSlide = 0;
    this.$next.html("Next");
    this.$next[0].style.display = 'flex';
    this.$restart[0].style.display = 'none';
    RECOMMENDATIONS_CONTAINER.html('');
    this.$legend.html('');

    this.updateSlide(this.currentSlide);
  },

  updateRecommendations: function(content) {
    this.updateLegend();
    RECOMMENDATIONS_CONTAINER.html('').html(content);
  },

  getConstraints: function() {
    var self = this;
    var constraints = $(self.$form).map(function() {
      var constraintCategories = [];
      for (var i = 0; i < (self.$slides.length - 1); i++) {
        var $question = $(self.$slides[i]);
        var checked = $question.find("input:checked");
        for (var ii = 0; ii < checked.length; ii++) {
          if ('categories' in checked[ii].dataset) {
            var checkedCategories = JSON.parse(checked[ii].dataset.categories);

            Array.prototype.push.apply(constraintCategories, checkedCategories);
          }
          if ("minPrice" in checked[ii].dataset) {
            self.productData.minPrice = checked[ii].dataset.minPrice;
          }
          if ("maxPrice" in checked[ii].dataset) {
            self.productData.maxPrice = checked[ii].dataset.maxPrice;
          }
        }
      }
      if (constraintCategories.length != 0) {
        var uniqueCategories = [];
        $.each(constraintCategories, function(i, el){
          if($.inArray(el, uniqueCategories) === -1) uniqueCategories.push(el);
        });
        self.productData.categories = uniqueCategories;
      }
      return {
          'categories': self.productData.categories,
          'minPrice': self.productData.minPrice,
          'maxPrice': self.productData.maxPrice
      };
    }).get();
    return constraints;
  },

  recommendationEvents: function() {
    var self = this;
    var $item = $('.product-guide-recommendation');
    var launch = $item.find('.product-guide-recommendation__info--details, .product-guide-recommendation__image');

    launch.on('click', function(e){
      var item = $(this).closest($item);
      self.openDetails(item);
    });
    self.$document.keyup(function(e) {
      if (!self.detailsOpen) return;
      if (e.keyCode === 27) self.closeDetails();
    });
  },

  /**
   * Build the product modal based off of the data attribute json
   * @param {json object} data - passed from each product recommendation
   */

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
    this.maskClose = d.querySelectorAll('.product-guide-details__mask')[0];
    this.buttonClose = d.querySelectorAll('.product-guide-details__close')[0];
    this.maskClose.addEventListener('click', $.proxy( this.closeDetails, this ));
    this.buttonClose.addEventListener('click', $.proxy( this.closeDetails, this ));
  },

  closeDetails: function() {
    this.resetDetails();
    this.detailsOpen = false;
    this.modal.classList.remove("details-open");
    this.$item.removeClass('details-open');

  }
}

module.exports = ProductGuideQuiz;

