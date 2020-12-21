/**
 * Initializes the comments module.
 * @constructor
 * @param {Object} el - The comments element.
 */
(function ( $ ) {
	var StickyPin = require( "@modules/main/sticky-pin/sticky-pin" );
  	var NAV_BREAKPOINT = 1024;
	module.exports = function ( el ) {

	  this.$el = $( el );
	  this.$window = $( window );
	  this.hideint = 0;
	  this.comments_init = false;
	  this.facebookComments = false;
	  this.$cta = $('.post__add-comment-cta');

	  this.init = function() {
	  	var t = this;

	  	$('body').on('click.comments', 'a[href="#comments"]', function(e) {
			e.preventDefault();

			var isOpen = t.$cta.hasClass( "open" );

			if ( !isOpen || $( this ).data( "toggle-comments" ) ) {

				if(!t.comments_init) {
					t.initComments();
				}

				t.$cta.toggleClass( "open" );

				/*$el.slideToggle( {
					'duration': 200,
					'step': function() {
						//StickyPin.checkPositions( true );
					}
				} );*/

				if ( isOpen ) {
					t.$el.addClass( "js-comments-closed" );
				} else {
					t.$el.removeClass( "js-comments-closed" );
					clearTimeout(t.hideint);
				}

				t.$window.trigger( "resize" );

				setTimeout(function(){
					t.$window.trigger( "resize" );
				}, 1500);
			}

		});

	  	t.$window.load(function() {
		    setTimeout(function(){
		    	if(!t.comments_init) {
		  	  		t.initComments();
		    	}
		    }, 5000);
		});

	  };

	  this.initComments = function() {
	  	var t = this;
	  	t.comments_init = true;

		// Disqus comments
		(function() {
			var d = document, s = d.createElement('script');
			s.src = '//wellgood.disqus.com/embed.js';
			s.setAttribute('data-timestamp', +new Date());
			(d.head || d.body).appendChild(s);
		})();

		// Facebook comments
		if ( true === this.facebookComments ) {
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=469237886594650";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		}

		var isOpen = t.$cta.hasClass( "open" );
		if(!isOpen) {
			t.hideint = setTimeout(function(){
			  	t.$el.addClass( "js-comments-closed" );
			  	t.$window.trigger( "resize" );
			}, 2500);
		}

	  };

	  this.init();

	}

})( jQuery );
