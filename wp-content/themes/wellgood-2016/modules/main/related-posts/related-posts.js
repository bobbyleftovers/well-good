(function ( $ ) {
  var throttle = require( "@modules/main/throttle/throttle" ),
      debounce = require( "@modules/main/debounce/debounce" );

  /**
   * Initializes the related posts header.
   * @constructor
   * @param {Object} el - The site related posts element.
   */
  function RelatedPosts( el ) {
    this.$el        = $( el );
    this.$displayAt = $( this.$el.data( "display-at" ) );
    this.$window    = $( window );
    this.$html      = $( 'html' );
    this.$cards     = this.$el.find( ".post-grid__card" );
    this.$content   = $('.post__inner');
    this.offset     = null;
    this.active     = false;
    this.currentPos = -1;
    this.lastScrolltop = 0;
    this.isRunning = false;
    this.currentPost = 0;

    this.initPosition();
    // this.$window.on( "resize", debounce( $.proxy( this.initPosition, this ) ) );
    this.$window.on( "scroll", throttle( $.proxy( this.maybeShow, this ), 500 ) );

    this.$el.on( "click", ".js-click-to-top", function ( e ) {
      e.preventDefault();
      $( "html, body" ).animate( {
        scrollTop: 0
      }, 300 );
    } );
  };

  RelatedPosts.prototype.initPosition = function () {
    this.calculateOffset();
    this.maybeShow();
    this.startRecommender();
  };

  RelatedPosts.prototype.calculateOffset = function () {
    this.offset = this.$displayAt.offset().top + this.$displayAt.height() - this.$el.height();
  }

  RelatedPosts.prototype.maybeShow = function () {
    if(this.$html.hasClass('js-search-bar-open')) return;

    if($("header.header .js-sub-menu-open").length) return;

    var scrollTop = this.$window.scrollTop();

    if ( scrollTop > this.offset && !this.active && this.lastScrolltop < scrollTop ) {
      this.$html.addClass( "js-related-posts-active" );
      this.active = true;
    } else if ( ( scrollTop <= this.offset && this.active ) || this.lastScrolltop > scrollTop ) {
      this.$html.removeClass( "js-related-posts-active" );
      this.active = false;
    }
    this.lastScrolltop = scrollTop;
  };

  RelatedPosts.prototype.startRecommender = function() {
    var context = this;
    if( !this.isRunning ){
      this.postSlider = setInterval(function(){
          if ( !context.$cards.eq(context.currentPost).length ){
            context.currentPost = 0;
          }
          if (context.$cards.filter('.js-card-active').length) {
            context.$cards.filter('.js-card-active').removeClass('js-card-active');
          }

          context.$cards.eq(context.currentPost).addClass('js-card-active');
          context.currentPost++;
          context.isRunning = true;
      }, 5000)
    }
  }

  RelatedPosts.prototype.resumeRecommender = function () {
    this.isRunning = false;
  }

  RelatedPosts.prototype.bypassRecommender = function() {
    this.isRunning = true;
  }

  module.exports = RelatedPosts;
})( jQuery );
