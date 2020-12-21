var $ = require('jquery')
var History = require('history')
var waypoint = require('@modules/main/waypoint/waypoint')
var firePageView = require('@modules/main/fire-page-view/fire-page-view')
var LOADING_MSG = $('#loading-state')
var NAV_ITEMS = document.querySelectorAll('.js-summer-subnav')
var ARTICLES_CONTAINER = $('.articles-container')
var MOBILE_MENU = document.getElementById('js-mobile-summer-subnav')
var MOBILE_MENU_VALUE = $('.summer-subnav__mobile-value')
import initModules from '@js/summer/init-modules.js'

function SummerLoadPosts(el) {
  this.pageTite = document.title
  this.requestUrl = window.location.href // For first page load
  this.currentUrl = window.location.href
  this.initLoad = true
  var pageID = this.getPageID()

  this.requestArticles(pageID)

  for (var ii = 0; ii < NAV_ITEMS.length; ii++) {
    NAV_ITEMS[ii].addEventListener('click', (ev) => {
      var clickedID = ev.target.getAttribute('data-id')
      var clickedUrl = ev.target.getAttribute('data-url')
      var clickedTitle = ev.target.getAttribute('data-title')
      this.navItemClicked(clickedID, clickedUrl, clickedTitle)
    })
  }

  MOBILE_MENU.onchange = (event) => {
    var clickedID = event.target.options[event.target.selectedIndex].dataset.id
    var clickedUrl = event.target.options[event.target.selectedIndex].dataset.url
    var clickedTitle = event.target.options[event.target.selectedIndex].dataset.title
    this.navItemClicked(clickedID, clickedUrl, clickedTitle)
  }
}

SummerLoadPosts.prototype.requestArticles = function (pageID) {
  var context = this
  $.ajax({
    url: '/wp-json/wellandgood/v1/summer-posts',
    type: 'GET',
    dataType: 'json',
    data: {
      post_id: pageID
    },
    beforeSend: function (xhr) {
      LOADING_MSG.removeClass('hidden')
      xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
    }
  }).done( function(data) {
    context.updateUrl()
    context.updateContent(data[0])

    initModules()
    context.applyWaypoints();

    if (!context.initLoad) {
      firePageView()
    } else {
      context.initLoad = false
    }
  }).error( function(error) {
    console.log(error)
  });
}

SummerLoadPosts.prototype.getPageID = function () {
  for (var i = 0; i < NAV_ITEMS.length; i++) {
    var navUrl = NAV_ITEMS[i].getAttribute('data-url').replace(/https?:\/\//i, "")
    if (this.currentUrl == navUrl || this.currentUrl.includes(navUrl)) {
      return NAV_ITEMS[i].getAttribute('data-id')
    }
  }
  return false
}

SummerLoadPosts.prototype.updateGradient = function (gradient) {
  $('.summer-wrapper').attr('style', gradient);
}

SummerLoadPosts.prototype.applyWaypoints = function () {
  var waypoints = $('.summer-posts-container').find('.waypoint');

  if (waypoints.length) {
    for (var i = 0; i < waypoints.length; i++) {
      waypoint(waypoints[i])
    }
  }
}

SummerLoadPosts.prototype.updateUrl = function () {
  if (this.currentUrl !== this.requestUrl) {
    var docTitle = this.title + ' | Well+Good'
    History.replaceState(null, docTitle, this.requestUrl)
  }
}

SummerLoadPosts.prototype.updateContent = function (content) {
  LOADING_MSG.addClass('hidden')
  ARTICLES_CONTAINER.html('').html(content)
}

SummerLoadPosts.prototype.navItemClicked = function (clickedID, clickedUrl, clickedTitle) {
  var activeNavItem = $('li[data-title="' + clickedTitle + '"]')
  var activeText = activeNavItem.text()
  var activeGradient = activeNavItem.attr('data-gradient')

  this.currentUrl = window.location.href
  this.requestUrl = clickedUrl
  this.title = clickedTitle
  this.updateActiveNav(activeNavItem)
  this.requestArticles(clickedID)
  this.updateGradient(activeGradient)
  MOBILE_MENU_VALUE.text(activeText)
}

SummerLoadPosts.prototype.updateActiveNav = function (activeNav) {
  $('.summer-subnav__item.active').removeClass('active')
  $(activeNav).addClass('active')
}

module.exports = SummerLoadPosts
