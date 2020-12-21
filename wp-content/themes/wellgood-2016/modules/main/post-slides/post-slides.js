
(function ( $ ) {
  var History  = require( "history" ),
      debounce = require( "@modules/main/debounce/debounce" ),
      throttle = require( "@modules/main/throttle/throttle"),
      firePageView = require( "@modules/main/fire-page-view/fire-page-view"),
      isStartScroll = false,
      unloadingPage = false,
      allowSrQuartiles = true;

  /**
   * Handle multiple slides within one post
   * @param {HTMLElement} el
   * @constructor
   */
  function PostSlides( el ) {
    var self          = this;
    this.$el          = $( el );
    this.$header      = $( ".header" );
    this.$window      = $( window );
    this.$slides      = this.$el.find( ".post__slide" );
    this.offsets      = [];
    this.windowHeight = null;
    this.headerOffset = null;
    this.activeSlide  = null;
    this.refresh      = $( el ).data('refresh-ads');
    this.orig_title   = (typeof __reach_config !== 'undefined') ? __reach_config.title.split(" - Slide")[0] : false; // strip the slide append from the original title, should it exist

    if( typeof __reach_config !== 'undefined' ){ // having a race condition issue on simplereach analytics, so let's timeout the offsets and position fix to prevent passing incorrect data
      __reach_config.manual_scroll_depth = true;
      this.quartiles = [];
      this.$window.on( "scroll.handler", throttle( $.proxy( this.scrollHandler, this ), 20 ));
      this.$window.on( "scroll.srQuart", throttle($.proxy(this.sendSimpleReachQuartiles, this ), 30 ));

      setTimeout($.proxy(this.calculateOffsets, this), 3050);
      setTimeout($.proxy(this.setPosition, this), 3070);
      setTimeout($.proxy(this.calculateQuartiles, this), 3075);


    } else {
      this.calculateOffsets();
      this.setPosition();
      this.$window.on( "scroll.handler", throttle( $.proxy( this.scrollHandler, this ), 500 ));
    }
    setTimeout($.proxy(this.calculateOffsets, this), 3050);

    // If the page is refreshed, the scroll behavior will malfunction and cause SimpleReach to track events improperly
    // So, we'll force the window to the top before we leave the page
    window.onbeforeunload = function () {
      if ( typeof __reach_config !== 'undefined' ) {
        unloadingPage = true
        window.scrollTo(0,0)
      }
    }

    this.clickEvent();
    this.$window.on( "resize", debounce( $.proxy( this.calculateOffsets, this ) ) );
  }

  /**
   * Set initial position based on URL fragments
   */
  PostSlides.prototype.setPosition = function () {
    var noQuery = location.href.split( "?" ),
        frags = noQuery[0].split( "/" ),
        slideNumber,
        endpoint;

    do {
      slideNumber = frags.pop();
    } while ( slideNumber == "" );

    endpoint = frags.pop();

    if ( endpoint != "slide" ) {
      return;
    }
    isStartScroll = true;
    this.scrollToSlide( slideNumber );
  };

  /**
   * Calculate top offsets of sections
   */
  PostSlides.prototype.calculateOffsets = function () {
    var windowHeight = this.$window.height();

    this.headerOffset = this.$header.height()

    this.offsets = this.$slides.map( function () {
      var $t = $( this );

      if( typeof __reach_config !== 'undefined' ){
        return $t.offset().top + $t.height();
      } else {
        return $t.offset().top + $t.height() - windowHeight * 0.25;
      }
    } );
  }

  /**
   * Retuns which slide is actively being viewed
   */
  PostSlides.prototype.getActiveSlide = function () {
    var scrollTop = window.pageYOffset;

    for ( var i = 0; i < this.offsets.length; i++ ) {
      if ( this.offsets[ i ] >= scrollTop ) {
        break;
      }
    }

    // in case scrolled all the way down
    if ( i  >= this.offsets.length ) {
      i = this.offsets.length - 1;
    }
    return i;
  }

  /**
   * Replace state of active slide
   */
  PostSlides.prototype.updateUrl = function () {
    var i = this.getActiveSlide(),
        href = this.$slides.eq( i ).data( "slide-url" );

    History.replaceState( {
      scrollTop: this.$window.scrollTop()
    }, document.title, href );
    this.activeSlide = i;
  }

  /**
   * Pushstate when scrolling.
   */
  PostSlides.prototype.scrollHandler = function () {
    var i = this.getActiveSlide(), // this.$window.scrollTop(),
        winloc = location.href,
        href;

    href = this.$slides.eq( i ).data( "slide-url" );

    if ( this.activeSlide !== i && winloc.indexOf(href) < 0 ) {
      this.activeSlide = i;
      var original_href = location.href; // need to store this before it's changed so that we can add a condition to the simplereach post based on the presence of query params

      // Update URL
      if( !isStartScroll ){
        this.updateUrl();
      }

      // If the post is implementhing the SimpleReach tracking script,
      // the __reach_config variable will be created inline, and thus available here
      // This will update the slide URL and pass the data to simplereach so that each slide
      // is tracked as an individual page-view.
      if( typeof __reach_config !== 'undefined' && this.$slides.length > 0 && original_href.indexOf('?') === -1 && !unloadingPage && !isStartScroll ) {

        __reach_config.url = href
        __reach_config.ajax = true
        __reach_config.title = this.appendSlideToTitle( href )

        SPR.Reach.collect(__reach_config)
      }

      // Refresh Ads
      if(typeof googletag !== 'undefined' && typeof googletag.pubads !== 'undefined' && this.refresh == 'refresh') googletag.pubads().refresh();

      // OLD GA analytics page view
      // if(typeof ga !== 'undefined') ga('send', 'pageview', window.location.pathname);

      firePageView();

    }
  };

  /**
   * Handle click events on "Next section" button
   */
  PostSlides.prototype.clickEvent = function () {
    var self = this;

    this.$el.on( "click", ".post__next-slide a", function ( e ) {
      e.preventDefault();

      var $t        = $( this ),
          nextSlide = $t.data( "slide" );

      self.scrollToSlide( nextSlide );
    } );
  };

  /**
   * Scroll to a particular slide
   * @param {int} slideNumber
   */
  PostSlides.prototype.scrollToSlide = function ( slideNumber ) {

    var $slide = $( "#slide-" + slideNumber ),
        context = this,
        pos;

    if ( !$slide.length ) {
      return;
    }

    if( typeof __reach_config !== 'undefined' ){
      pos = $slide.offset().top;
    } else {
      pos = $slide.offset().top - this.headerOffset;
    }

    $( "html, body" ).animate( {
      scrollTop: pos
    }, 300, 'swing', function() {
      isStartScroll = false;
      context.updateUrl()
    });


  };

  /**
   * Append slide number to post title for simplereach data
   * @param {string} url
   */
  PostSlides.prototype.appendSlideToTitle = function ( url ) {
    var slide_number = parseInt(url.split('/')[url.split('/').length - 2])
    var slide_append = ( !isNaN(slide_number) ) ? ' - Slide ' + slide_number : ''

    return this.orig_title + slide_append
  }

  /**
   * Determine the percent of each slide that has been scrolled past and trigger
   * the approriate tracking events to send that info to simplereach
   */
  PostSlides.prototype.sendSimpleReachQuartiles = function ( ) {
    if( allowSrQuartiles ){

      if( typeof __reach_config !== 'undefined' && !isStartScroll ){
        var activeSlide = this.getActiveSlide(),
        slideOffset = $('#' + this.$slides[activeSlide].id).offset().top;

        for ( q = 0; q < this.quartiles[activeSlide].length; q++ ) {
          var quartileThreshold = slideOffset + this.quartiles[activeSlide][q][0]

          if( scrollY > quartileThreshold && this.quartiles[activeSlide][q][1] == false ) {
            var quart = 100 / (4  / (q + 1));
            this.quartiles[activeSlide][q][1] = true

            SPR.scrollDepthReached(quart);
          }
        }

        if( (activeSlide + 1) == this.quartiles.length && this.quartiles[activeSlide][3][1] == true ){
          allowSrQuartiles = false;
        }
      }
    }

  }

  /**
   * Determine the top offsets of each slide's quartile and store it in
   * an array to be evaluated for the SimpleReach tracking script.
   * This follows a similar process to how the page url is updated
   */
  PostSlides.prototype.calculateQuartiles = function () {

    for (var xx = 0; xx < this.$slides.length; xx++){
      var nextSlide = xx + 1,
      currentHeight = this.$slides[xx].clientHeight,
      quartile;
      this.quartiles[xx] = []

      for( var x = 1; x <= 4; x++ ){
        if( x === 4 ){
          // need to add some padding to the bottom of the slide because
          // 100% of a slide = the same point on the page where a new simplereach page-view is fired,
          // so it would fire on the wrong slide
          quartile = currentHeight * (x * 0.25) - 50
        } else {
          quartile = currentHeight * (x * 0.25)
        }
        this.quartiles[xx].push([quartile,false])


        // guidlines
        // var guideoffset = $(this.$slides[xx]).offset().top + quartile ;

        // if( x === 1 ){
        //   $('body').prepend('<span class="slideguide slide-' + xx + '-start"></span>');
        // }

        // $('body').prepend('<span class="slideguide slide-' + xx + '-' + x + '"></span>');

        // $('.slideguide.slide-' + xx + '-' + x).css({
        //   'position' : 'absolute',
        //   'z-index' : 99999999,
        //   'height' : '2px',
        //   'width' : '100vw',
        //   'background-color' : 'red',
        //   'left' : 0,
        //   'top' : guideoffset + 'px'
        // });

        // $('.slideguide.slide-' + xx + '-start').css({
        //   'position' : 'absolute',
        //   'z-index' : 99999999,
        //   'height' : '2px',
        //   'width' : '100vw',
        //   'background-color' : 'blue',
        //   'left' : 0,
        //   'top' : $(this.$slides[xx]).offset().top + 'px'
        // });
      }
    }
  }

  PostSlides.prototype.disable = function () {
    this.$window.off('scroll.handler');
    this.$window.off('scroll.srQuart');
  }
  module.exports = PostSlides;
})( jQuery );
