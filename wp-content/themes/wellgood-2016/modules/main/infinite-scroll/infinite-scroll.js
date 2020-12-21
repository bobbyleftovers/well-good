var $ = require( 'jquery' )
var throttle = require("@modules/main/throttle/throttle")
var History  = require( "history" )
var initializeModules = require("lib/init-modules")
var firePageView = require( "@modules/main/fire-page-view/fire-page-view")
var postSlides = require("@modules/main/post-slides/post-slides")
var Advertisement = require("@modules/main/advertisement/advertisement")

function InfiniteScroll( el ) {

  // Elements
  this.$el = $(el)
  this.$articles = this.$el.children('article.post')
  this.initialPost = $('.infinite-scroll').attr('data-post-id')
  this.presetPost = $('.infinite-scroll').attr('data-preset-id')
  this.vertical = $('.infinite-scroll').attr('data-vertical')

  // Tracking
  this.instance = 0
  this.offsets = []
  this.infinitePageViews = []
  this.headerOffset = null
  this.activeArticle = null
  this.loadingPost = false
  this.slideShow = null

  // Settings
  this.infiniteScrollLimit = 10
  this.bottomOffset = 2000

  this.events()
  this.calculateOffsets()
  this.postSlides()
}

InfiniteScroll.prototype = {

  /**
   * Events
   */
  events: function() {
    $(window).on('scroll', throttle( $.proxy(this.scrollHandler, this), 150))
  },

  /**
   * Check position of scroll
   */
  scrollHandler: function () {
    const i = this.getActiveArticle()
    const location = this.getWindowLocation()
    const href = this.$articles.eq(i).data('url')

    if (this.activeArticle !== i && location.indexOf(href) < 0) {
      this.activeArticle = i
      this.updateUrl()
      this.updateAdhesion()
      this.postSlides()
    }

    this.loadNextPost()
  },

  loadNextPost: function () {
    var self = this
    var $container = $('.post-content__infinite-wrapper')
    var $loadMore = $('.load-more-indicator__text')
    var containerOffset = $container.offset().top
    var containerBottomOffset = containerOffset + $container.height() - $(window).height()
    var scrollArea = $(window).scrollTop() > ( containerBottomOffset - this.bottomOffset )

    if ( this.checkInfiniteScrollLimit() && !this.loadingPost && scrollArea) {
      this.loadingPost = true
      $.ajax({
        url: '/wp-json/wellandgood/v1/infinite-scroll',
        type: 'GET',
        dataType: 'json',
        data: {
          instance: ++this.instance,
          initialPost: this.initialPost, // ID of the initially queried post
          presetPost: this.presetPost, // ID of the preset post
          vertical: this.vertical, // vertical to call
          blacklistedPartners: blacklistedPartners ? JSON.stringify(blacklistedPartners) : null
        },
        beforeSend: function (xhr) {
          $loadMore.removeClass('not-showing')
          xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce )
        }
      }).done( function(data) {
        var postId = data[0]
        var postMarkup = data[1]
        $('.infinite-scroll').before(postMarkup)
        initializeModules(name => require( `@modules/main/${name}/${name}.js` ))
        self.advertisement()
        self.calculateOffsets()
        self.loadingPost = false
        self.initializeImageLinks(postId)
        $loadMore.addClass('not-showing')
      }).fail( function(error) {
        console.log(error)
      })
    }
  },

  checkInfiniteScrollLimit: function() {
    return this.instance < this.infiniteScrollLimit
  },

  initializeImageLinks: function (postID) {
    var postContainer = document.querySelector('article#post-' + postID)
    var imageLinksContainers = postContainer ? postContainer.querySelectorAll('div[data-module-init="imagelinks"] .imagelinks-script') : null

    if (imageLinksContainers && imageLinksContainers.length) {
      for (var i = 0; i < imageLinksContainers.length; i++) {
        var script = imageLinksContainers[i].innerHTML
        var funcName = script.match(/imgl_init(.*?)(?=\()/)[0]

        window.settings = {
          functionName: funcName
        }

        window[settings.functionName]()
      }
    }
  },

  getWindowLocation: function () {
    var url = window.location.href

    return url.replace(/slide\/\d+\//,'')
  },

  updateAdhesion: function () {
    var adhesionUnits = document.querySelectorAll('.container__ad--adhesion')
    for (var i = 0; i < adhesionUnits.length; i++) {
      var unit = adhesionUnits[i]
      if (parseInt(unit.dataset.adPage) === this.activeArticle) {
        unit.classList.add('show-mobile')
      } else {
        unit.classList.remove('show-mobile')
      }
    }
  },

  updateUrl: function (el) {
    var href = this.$articles.eq( this.activeArticle ).data('url')
    var datalayer_data = this.$articles.eq( this.activeArticle ).data('datalayer')
    var permutive_data = this.$articles.eq( this.activeArticle ).data('permutive')
    var pageTitle = this.$articles.eq( this.activeArticle ).data('title') + ' | Well+Good'
    var infinitePosition = parseInt(datalayer_data.scroll)

    History.replaceState({
      scrollTop: $(window).scrollTop()
    }, pageTitle, href)

    // For analytics tracking
    if (infinitePosition > 0 && this.infinitePageViews.indexOf(infinitePosition) === -1) {
      this.infinitePageViews.push(infinitePosition)
      firePageView([datalayer_data, permutive_data])
    } else {
      firePageView([datalayer_data, permutive_data], false)
    }
  },

  calculateOffsets: function() {
    var windowHeight = $(window).height()
    this.headerOffset = $('.header').height()
    this.$articles = this.$el.children('article.post')

    this.offsets = this.$articles.map( function () {
      var $t = $( this )
      var articleBottom = $t.offset().top + $t.height()
      var nextAdHeight = $t.next('.container__ad--adhesion').height()

      return articleBottom - ($(window).height() - nextAdHeight)
    } )
  },

  postSlides: function () {
    if ( this.$articles.eq(this.activeArticle).find('.post__slide').length && this.slideShow ) return
    if ( this.$articles.eq(this.activeArticle).find('.post__slide').length ) {
      this.slideShow = new postSlides(this.$articles.eq(this.activeArticle))
    } else {
      if ( this.slideShow ){
        this.slideShow.disable()
        this.slideShow = null
      }
    }
  },

  advertisement: function () {
    var body = document.querySelector('body')
    var newAds = new Advertisement(body)

    newAds.buildAds(this.instance)
  },

  /**
   * Retuns which slide is actively being viewed
   */
  getActiveArticle: function () {
    var scrollTop = (window.pageYOffset)
    for ( var i = 0; i < this.offsets.length; i++ ) {
      if ( this.offsets[ i ] >= scrollTop ) {
        break
      }
    }

    // in case scrolled all the way down
    if ( i  >= this.offsets.length ) {
      i = this.offsets.length - 1
    }
    return i
  }
}

module.exports = InfiniteScroll
