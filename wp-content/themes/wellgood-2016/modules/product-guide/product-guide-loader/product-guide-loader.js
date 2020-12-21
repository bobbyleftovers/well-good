var $ = require('jquery');
var History = require('history');
var firePageView = require('@modules/main/fire-page-view/fire-page-view');
var LOADER = $('.product-guide-loader');
var NAV_ITEMS = document.querySelectorAll('.load-products');
var MAIN_CONTAINER = $('.main-wrapper');
var SCROLL_CONTAINER = $('.scroll-wrapper');
var HEADER_CONTAINTER = $('.product-guide-header');
var HERO_CONTAINTER = $('.product-guide-hero');
var PRODUCTS_CONTAINER = $('.product-guide-grid');
var SIDEBAR = $('.product-guide-sidebar');
var QUIZ_CONTAINER = $('.product-guide-quiz');
var MOBILE_MENU = document.getElementById('product-guide-page-select');
var MOBILE_MENU_VALUE = $('.product-guide-subnav__mobile-value');
var initializeModules = require("lib/init-modules");

function ProductGuideLoader(el) {
  this.pageTite = document.title;
  this.requestUrl = window.location.href; // For first page load
  this.currentUrl = window.location.href;
  this.animationComplete = false;

  this.events();
}

ProductGuideLoader.prototype = {
  events: function() {
    var self = this;

    $('.load-products').on('click', function(e) {
      e.preventDefault();

      var clickedID = $(this).data('id');
      var clickedUrl = $(this).data('url');
      var clickedTitle = $(this).data('title');
      self.navItemClicked(clickedID, clickedUrl, clickedTitle);
    });

    MOBILE_MENU.onchange = function(e) {
      var clickedID = e.target.options[e.target.selectedIndex].dataset.id;
      var clickedUrl = e.target.options[e.target.selectedIndex].dataset.url;
      var clickedTitle = e.target.options[e.target.selectedIndex].dataset.title;

      self.navItemClicked(clickedID, clickedUrl, clickedTitle);
    }
  },

  requestArticles: function (pageID) {
    var self = this;

    $.ajax({
      url: '/wp-json/wellandgood/v1/product-guide-products',
      type: 'POST',
      dataType: 'json',
      data: {
        post_id: pageID
      },
      beforeSend: function (xhr) {
        LOADER.addClass('loading');
        PRODUCTS_CONTAINER.removeAttr('data-module-fired');
        QUIZ_CONTAINER.removeAttr('data-module-fired');
        SIDEBAR.removeAttr('data-module-fired');
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        setTimeout(function() {
          self.animationComplete = true;
        }, 1000); // NOTE: do we hook this into the css value?
      }
    }).done( function(data) {
      if (self.animationComplete) {
        self.updateUI(data);
      } else {
        // keep checking until animation has completed
        var checkUI = setInterval(function() {
          if (self.animationComplete) {
            clearInterval(checkUI);
            self.updateUI(data);
          }
        }, 100);
      }

    }).error( function(error) {
      console.log(error);
    });
  },

  updateUI: function(data) {
    var self = this;
    self.updateUrl();
    self.updateHero(data[1]);
    self.updateHeader(data[0]);
    self.updateContent(data[2]);
    LOADER.addClass('loaded');
    self.resetScroll();

    setTimeout(function() {
      LOADER.removeClass('loading loaded');
    }, 1000);

    initializeModules(name => require( "@modules/product-guide/" + name + '/' + name + '.js' ))

    firePageView();
    self.events();
  },

  updateUrl: function () {
    if (this.currentUrl !== this.requestUrl) {
      var docTitle = this.title + ' | Well+Good';
      History.replaceState(null, docTitle, this.requestUrl);
    }
  },

  updateContent: function (content) {
    PRODUCTS_CONTAINER.html('').html(content);
  },

  updateHero: function (content) {
    HERO_CONTAINTER.remove();
    SCROLL_CONTAINER.prepend(content);

    // reset hero containers
    HERO_CONTAINTER = $('.product-guide-hero');
  },

  updateHeader: function (content) {
    HEADER_CONTAINTER.remove();
    MAIN_CONTAINER.prepend(content);

    // reset header containers
    HEADER_CONTAINTER = $('.product-guide-header');
    MOBILE_MENU = document.getElementById('product-guide-page-select');
    MOBILE_MENU_VALUE = $('.product-guide-subnav__mobile-value');
  },

  navItemClicked: function (clickedID, clickedUrl, clickedTitle) {
    var activeNavItem = $('li[data-title="' + clickedTitle + '"]');
    var activeText = activeNavItem.text();

    this.currentUrl = window.location.href;
    this.requestUrl = clickedUrl;
    this.title = clickedTitle;
    this.updateActiveNav(activeNavItem);
    this.requestArticles(clickedID);
    MOBILE_MENU_VALUE.text(activeText);
  },

  updateActiveNav: function (activeNav) {
    $('.product-guide-subnav__item.active').removeClass('active');
    $(activeNav).addClass('active');
  },

  resetScroll: function() {
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    document.body.scrollLeft = document.documentElement.scrollLeft = 0;
  },

  getPageID: function () {
    for (var i = 0; i < NAV_ITEMS.length; i++) {
      var navUrl = NAV_ITEMS[i].getAttribute('data-url');

      if (this.currentUrl == navUrl) {
        return NAV_ITEMS[i].getAttribute('data-id');
      }
    }
    return false;
  }
}

module.exports = ProductGuideLoader;
