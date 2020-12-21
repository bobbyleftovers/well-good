var $ = require('jquery')
var stickybits = require('stickybits/dist/stickybits')
var debounce = require('@modules/main/debounce/debounce')
var throttle = require('@modules/main/throttle/throttle')

var NAV_BREAKPOINT = 1024
var LEADERBOARD_BREAKPOINT = 999

function Header(el) {
    var t = this
    this.$el = $(el)
    this.$window = $( window )
    this.$html = $('html')
    this.$body = $('body')
    this.html = document.querySelectorAll('html')[0]
    this.header = document.querySelectorAll('.header')[0]
    this.scrollDistance = 0
    this.horizontalRunway = (this.header.offsetHeight - window.innerHeight) * -1
    this.$adWrapper = $('.container__ad')
    this.throttleDelay = 0
    this.throttleNavDelay = 0
    this.throttleSearchDelay = 0
    this.$related = $('.related-posts--header')
    this.$ad = this.$adWrapper.find('.advertisement')
    this.$menuDrawer = $('.menu-drawer-container')
    this.search = '.header__search'
    this.menuIsOpen = false
    this.searchIsOpen = false
    this.headerTopSignup = $('.header-top [data-signup-form-toggle]')
    this.headerTopSignupForm = $('.header-top [data-signup-form]')
    this.searchOverlay = $('.search-overlay')

    this.events()
    this.stickAds()
    this.initMenu()
    this.initSearch()
    this.initNL()
    this.reFocusTabs()
    window.onresize = function(event) {
        t.events()
    }
}

Header.prototype = {
    /**
     * Set events
     */
    events: function() {
        stickybits('.header')
        this.scrolling()

        window.addEventListener('scroll', this.scrolling.bind(this))
        document.addEventListener('keyup', this.keyPressHandler.bind(this))
    },

    /**
     * Slide down the header bar if it's past the tall header offset
     */
    scrolling: function(e) {
        var scrollTop = $(window).scrollTop()
        var elementOffset = $(this.header).offset().top
        var topped = (elementOffset - scrollTop) == 0

        if (topped) {
            this.stickHeader()
        } else if (!topped) {
            this.unstickHeader()
        }
    },

    /**
     * Control the opening of the nav
     */
    stickHeader: function() {
        this.header.classList.add('header--is-stuck')
    },

    /**
     * Control the closing of the nav and its speed
     */
    unstickHeader: function() {
        this.header.classList.remove('header--is-stuck')
    },

    /**
     * Initialize menu interactions
     */
    initMenu: function() {
        this.initSubMenus()
        this.responsiveMenu()
        this.$window.on( "resize", debounce( $.proxy( this.responsiveMenu, this ) ) )
    },

    /**
     * Init sub menus
     */
    initSubMenus: function() {
        var t = this
        t.$toggles = t.$el.find( "[class*='js-toggle-nav__']" )
        t.$subMenus = t.$el.find( ".sub-menu" )

        t.$toggles.each(function(toggleIndex, toggle) {
            var $toggle = $(toggle)
            var classes = $toggle.attr( 'class' ).split(/\s+/ )
            var regExp = /js-toggle-nav__/
            var foundClass
            var $subMenu
            var i

            for (i = 0; i < classes.length; i++) {
                if (regExp.test( classes[i])) {
                    foundClass = classes[i]
                    break
                }
            }

            if (foundClass) {
                $subMenu = t.$subMenus.filter( "[data-toggle-class='" + foundClass + "']" )

                if ( ! $subMenu.length ) {
                    return
                }

                if ($toggle.closest( ".menu-item" ).length) {
                    $subMenu.data( "toggle", $toggle )
                }

                $toggle.data( "sub-menu", $subMenu )
            }
        })
    },

    /**
     * Control for menu on different sizes
     */
    responsiveMenu: function() {
        var t = this
        var $toggleOff = t.$el.find('a, .header__search')
        var $document = $(document)
        var hoverIntentTimeout

        t.$el.off( "click.nav touchstart.nav" )
        $document.off( "mouseenter.nav mouseleave.nav mouseout.nav" )
        $toggleOff.off( "mouseenter.nav" )

        function hideActiveSubNav() {
            t.$subMenus.removeClass("fade-transition").css('opacity', "")
            var $current = t.$subMenus.filter( ".js-sub-menu-open" )

            if ( $current.length ) {
                toggleSubNav( $current, false )
            }
        }

        function toggleSubNav( $subMenu, state ) {
            if ( ! $subMenu || ! $subMenu.length ) return
            var $toggle = $subMenu.data( "toggle" )

            if ( $toggle.length ) {
                $toggle.toggleClass( "js-hovered", state )
            }
            $subMenu.toggleClass( "js-sub-menu-open", state )
            t.$window.trigger( "scroll" )
        }

        function toggle( $subMenu ) {
            if ( !$subMenu || !$subMenu.hasClass( "js-sub-menu-open" ) ) {
                hideActiveSubNav()
            }

            toggleSubNav( $subMenu )
        }

        if ( t.$window.width() >= NAV_BREAKPOINT ) {
            $document.on( "mouseenter.nav", "[class*='js-toggle-nav__']", function() {
                if (t.html.classList.contains('js-search-bar-open')) return
                var $subMenu = $( this ).data( "sub-menu" )

                t.throttle(function() {
                    var $current = t.$subMenus.filter('.js-sub-menu-open')
                    if ($current.length && typeof $subMenu !== 'undefined') {
                        if ($subMenu.hasClass("js-sub-menu-open")) return
                        $current.addClass("fade-transition").css("opacity", 1)
                        $subMenu.addClass("fade-transition").css("opacity", 0)
                        setTimeout(function() {
                            $current.css("opacity", 0)
                            $subMenu.addClass("js-sub-menu-open").css("opacity", 1)
                            setTimeout(function() {
                                $('[class*="js-toggle-nav__"]').removeClass('js-hovered')
                                $subMenu.data('toggle').addClass('js-hovered')
                                $current.removeClass('js-sub-menu-open').css('opacity', '')
                                $subMenu.removeClass('fade-transition').addClass("js-sub-menu-open").css('opacity', '')
                                setTimeout(function() {
                                    $current.removeClass("fade-transition")
                                }, 10)
                            }, 250)
                            t.$window.trigger( "scroll" )
                        }, 50)
                    } else {
                        toggle($subMenu)
                    }
                    t.$window.trigger( "scroll" )
                }, 250)
            })

            $document.on( "mouseout.nav", function(e) {
                var el = e.relatedTarget|| e.toElement
                if(!el || el.nodeName == 'HTML') {
                    throttle(function() {
                        hideActiveSubNav()
                    }, 100)
                }
            })

            $document.on( "mouseleave.nav", ".js-sub-menu-open", function() {
                t.throttle(function() {
                    hideActiveSubNav()
                }, 200)
            })

            $document.on( "mouseenter.nav", ".header .logo, .header__search, .menu-social-menu-container a", function() {
                hideActiveSubNav()
            })

            $document.on( "mousemove.nav", "body", function(event) {
                var $target = $(event.target)
                if ($target.length) {
                    t.throttle(function() {
                        if (!$target.closest("[class*='js-toggle-nav__']").length && !$target.closest(".sub-menu").length) {
                            hideActiveSubNav()
                        }
                    }, 300, 'throttleNavDelay')
                }
            })

            t.html.classList.remove( "js-nav__primary-open" )
        } else {
            t.$el.on( "click.nav", "[class*='js-toggle-nav__']", function() {
                var $toggle = $(this)
                t.throttle(function() {
                    toggle($toggle.data( "sub-menu"))
                })
            })

            t.$el.on( "click.nav", "[class*='js-toggle-nav__']", function( e ) {
                e.preventDefault()
            })
        }

        // Toggle Nav
        t.$el.on( "click.nav touchstart.nav", ".nav-trigger", function (e) {
            t.throttle(function() {
                t.openMenu()
                t.reFocusTabs()
            })
            e.preventDefault()
        })

        this.$menuDrawer.on("click", ".js-drawer-close", function(e) {
            t.throttle(function() {
                t.closeMenu()
                t.reFocusTabs()
            })
            e.preventDefault()
        })

        $document.on( "touchstart.nav", "[class*='js-toggle-nav__']", function(e) {
            e.preventDefault()
            var $subMenu = $( this ).data( "sub-menu" )

            t.throttle(function() {
                toggle( $subMenu )
            }, 300)
        })
    },

    /**
     * Open side menu
     */
    openMenu: function() {
        if (this.menuIsOpen) {
            return
        }

        this.$html.addClass( "js-nav__primary-open" )
        this.menuIsOpen = true
    },

    /**
     * Close side menu
     */
    closeMenu: function() {
        if (!this.menuIsOpen) {
            return
        }

        this.$html.removeClass( "js-nav__primary-open" )
        this.menuIsOpen = false
    },

    /**
     * Initiate search element
     */
    initSearch: function() {
        var self = this
        var $input = $($(self.search).data('search-elem'))

        $(document).on( "click.search", function(e) {
            var target = e.relatedTarget || e.toElement
            var field = $(target).closest(self.search + ', .search-bar')
            if ($input.length && $input.get(0).contains(e.target)) return
            if (!field.length && self.searchIsOpen) {
                self.throttle(function() {
                    self.closeSearch($input)
                }, 150, 'throttleSearchDelay')
            }
        })
        self.$el.on( "click.search", self.search, function() {
            self.throttle(function() {
                if (self.$html.hasClass( "js-search-bar-open")) {
                    self.closeSearch($input)
                } else {
                    self.openSearch($input)
                }
            }, 150, 'throttleSearchDelay')
        })
    },

    initNL: function() {
      $(document).on('click', (e) => {
        if (this.headerTopSignup.length) {
          if (this.headerTopSignup.length && this.headerTopSignup.is($(e.target)) || this.headerTopSignup.get(0).contains(e.target)) {
            e.preventDefault()
            $('html').toggleClass('js-header-top__signup-form-active')
            return
          }

          if (this.headerTopSignupForm.length && this.headerTopSignupForm.get(0).contains(e.target)) return
          $('html').removeClass('js-header-top__signup-form-active')
        }
      })
    },

    /**
     * Open search bar
     */
    openSearch: function($input) {
        if (this.searchIsOpen) {
            return
        }
        $('.container__ad.container__ad--horizontal').css({ zIndex: 2100 }).addClass('bg-white')
        this.$html.addClass( "js-search-bar-open" )
        if ($input.length) {
            this.throttle(function() {
                $input.focus()
            }, 750)
        }
        $input.closest('.header__search').attr('aria-expanded', true)
        this.searchIsOpen = true
    },

    /**
     * Close search bar
     */
    closeSearch: function($input) {
        if (!this.searchIsOpen) {
            return
        }
        this.$html.removeClass( "js-search-bar-open" )
        setTimeout(function() {
          $input.val('')
        }, 300)

        $input.closest('.header__search').attr('aria-expanded', false)
        this.searchIsOpen = false
        $('.container__ad.container__ad--horizontal').css({ zIndex: '' })
    },

    /**
     * Control for escape key when different
     * menus are open
     */
	keyPressHandler: function(e) {
        if (e.keyCode !== 27) {
            return
        }

		if (this.menuIsOpen) {
			this.closeMenu()
		} else if (!this.menuIsOpen && this.searchIsOpen) {
            this.closeSearch()
        }
	},

    stickAds: function() {
        var t = this

        // Do not run stick ad headers on home page
        if( !this.$body.hasClass('page-template-page-home') ){
            var a_fired = false;
            // Make sure that googletag.cmd exists.
            window.googletag = window.googletag || {};
            googletag.cmd = googletag.cmd || [];
            // Correct: Queueing the callback on the command queue.
            googletag.cmd.push(function() {
                googletag.pubads().addEventListener('slotRenderEnded', function(event) {
                    if (event.slot.A && event.slot.A.pos[0] == 'a' && !t.$body.hasClass('ad-adjusted')) {
                        var adHeight = event.size[1];
                        t.adjustAd( adHeight );
                        // t.startTimers();

                        a_fired = true;
                    }

                })
            })
            this.$ad.find('iframe').ready(function(){
                setTimeout(function(){
                    if (!a_fired) {
                        t.adjustAd( parseInt( $('.header__wrapper').find('iframe').attr('height')) );
                        // t.startTimers();
                    }
                }, 200);

            });
        }
    },

    // startTimers: function () {
    //     var t = this

    //     if ( ! t.adTimer ) {
    //     t.adTimer = setTimeout(function() {

    //         // if user has scrolled down, slide it off
    //         if( t.$window.scrollTop() > 10 ){
    //             t.$ad.slideUp(300, function() {
    //                 t.$body.css('paddingTop', '')
    //                 t.$adWrapper.addClass('unstick')
    //                 t.$ad.show()
    //             })
    //         } else {
    //             // if we're towards the top of the page, just kill it
    //             t.$body.css('paddingTop', '')
    //             t.$adWrapper.addClass('unstick')
    //             t.$ad.css('display','block')
    //         }

    //         t.$related.css('top', '')
    //     }, 3000)
    //     }
    // },

    // stopTimers: function () {
    //     var t = this;
    //     clearTimeout(t.adTimer);
    //     t.adTimer = null;
    // },

    adjustAd: function (adHeight) {
        var t = this;
        if (!t.$adWrapper.length) {
            return;
        }

        if (t.$window.outerWidth() >= LEADERBOARD_BREAKPOINT) {
            var offset = adHeight + t.$adWrapper.find('.header__inner').outerHeight() + 'px';

            t.$body.css('paddingTop', offset).addClass('ad-adjusted');
            t.$related.css('top', adHeight);



            return;
        }

        t.$body.css('paddingBottom', adHeight + 'px');
    },

    /**
     * Control the tabbing order when side menu is
     * opened or closed
     */
    reFocusTabs: function() {
        var menuInputs = this.$menuDrawer.find('select, input, textarea, button, a').filter(':visible')

        if (this.$html.hasClass('js-nav__primary-open')) {
            for (var i = 0; i < menuInputs.length; i++) {
                var element = $(menuInputs[i])
                element.removeAttr('tabindex')
                element.removeAttr('aria-hidden')
            }
            menuInputs.first().focus()

            menuInputs.last().on('keydown', function (e) {
                if ((e.which === 9 && !e.shiftKey)) {
                    e.preventDefault()
                    menuInputs.first().focus()
                }
            })

            menuInputs.first().on('keydown', function (e) {
                if ((e.which === 9 && e.shiftKey)) {
                    e.preventDefault()
                    menuInputs.last().focus()
                }
            })
        } else {
            for (var i = 0; i < menuInputs.length; i++) {
                var element = $(menuInputs[i])
                element.attr('tabindex', -1)
                element.attr('aria-hidden', 'true')
            }
        }
    },

    /**
     * Trottle events for touch
     */
    throttle: function(action, threshold, key) {
        if (typeof threshold === 'undefined') {
            threshold = 100
        }
        if (typeof key === 'undefined') {
            key = "throttleDelay"
        }
        clearTimeout(this[key])
        this[key] = setTimeout(function(){
            action()
        }, threshold)
    }
}

module.exports = Header
