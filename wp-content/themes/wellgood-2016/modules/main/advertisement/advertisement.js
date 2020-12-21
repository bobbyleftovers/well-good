/**
 * External Variables
 * Set in `templates/partials/header/ad-vars-head.php`
 *
 * @var ISMOBILE boolean - Returns true if viewport is smaller than mobile breakpoint
 * @var ADINTERVALS array - An empty array for setting timeouts when loading the ads
 * @var ADCODES object - An empty object
 * @var ADSREADY boolean
 * @var BREAKPOINT integer
 * @var ADCONFIG object
 */


/**
 * Dependencies
 */
var $ = require( 'jquery' )
var stickybits = require('stickybits/dist/stickybits')


function Advertisement(el) {

	if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) return;

	this.body = el
	this.header = document.querySelector('.header')
	this.sponsorBanner = document.querySelector('.post-hero__sponsor-banner')

  this.offset = 0
	if (this.header) {
		this.offset += this.header.offsetHeight
	}
  if (this.sponsorBanner) {
    this.offset += this.sponsorBanner.offsetHeight
  } else {
		this.offset += 20
	}

	this.events()
}

Advertisement.prototype = {
	events: function() {
		if (!Object.keys) Object.keys = function(o) {
			if (o !== Object(o))
				throw new TypeError('Object.keys called on a non-object')
			var k = [], p
			for (p in o) if (Object.prototype.hasOwnProperty.call(o,p)) k.push(p)
			return k
		}

		window.addEventListener('resize', this.resizeHandler.bind(this))

		if (!this.body.hasAttribute('data-ads-loaded')) {
			this.buildAds()
			this.body.setAttribute('data-ads-loaded', true)
		}
	},

	/**
	 * Reset ISMOBILE variable when screen
	 * is below breakpoint
	 */
	resizeHandler: function() {
		var isMobile = window.innerWidth <= BREAKPOINT
		if (isMobile === ISMOBILE) return

		ISMOBILE = isMobile
	},

	/**
	 *
	 */
	compileContainers: function() {
		var size = ISMOBILE ? 'mobile' : 'desktop'
		var bodyClasses = this.body ? this.body.className.split(' ') : []

		return Object.keys(ADCONFIG).reduce(function (filtered, key) {
			if (ADCONFIG[key].size.includes(size)) {
				filtered[key] = ADCONFIG[key].container
			}

			return filtered
		}, {})
	},

	/**
	 *
	 *
	 * @param {int} page
	 */
	buildAds: function(page) {
		var targets = this.compileContainers()
		for (var slot in targets) {
			var target = targets[slot]
			var container = typeof page === 'undefined' ? target : target + "[data-ad-page='" + page + "']"
			var ads = document.querySelectorAll(container)

			for (var i = 0; i < ads.length; i++) {
				var container = ads[i]
				var parent = container.closest(target.parent)
				var data = {}

				data.slot = slot

				if (typeof page !== 'undefined') {
					data.page = page
				} else if (typeof page === 'undefined' && container.hasAttribute('data-ad-page')) {
					data.page = container.dataset.adPage
				} else if (typeof page === 'undefined' && parent && parent.hasAttribute('data-instance')) {
					data.page = parent.dataset.instance
				} else {
					data.page = 0
				}

				if (container && container.hasAttribute('data-ad-iteration')) {
					data.iteration = container.dataset.adIteration
				} else {
					data.iteration = 0
				}

				this.requestAd(container, data)
			}
		}
	},

	/**
	 * Send AJAX request to get ad markup
	 *
	 * @param {string} container
	 * @param {object} data
	 */
	requestAd: function(container, data) {
		var self = this
		var slot = data.slot
		var page = data.page
		var iteration = data.iteration

		$.ajax({
			url: '/wp-json/wellandgood/v1/get-inline-ad',
			type: 'GET',
			dataType: 'json',
			data: {
				slot: slot,
				page: page,
				iteration: iteration,
			}
		}).done(function(markup) {
			self.injectAd(slot, container, markup)
		}).fail(function(error) {
			console.log(error)
		})
	},

	/**
	 *
	 *
	 * @param {string} slot - name of ad slot
	 * @param {string} container
	 */
	displayAd: function(slot, container) {
		var id = container.querySelector('.advertising-adslot') ? container.querySelector('.advertising-adslot').getAttribute('id') : null

		if (id) {
			var loadId = id + '-loaded'
			var readyId = id + '-ready'

			this.logAdId(slot, id)

			ADINTERVALS[readyId] = setInterval(function() {
				if (!ADSREADY) return

				clearInterval(ADINTERVALS[readyId])

				googletag.cmd.push(function() {
          googletag.display(id)
				})

				ADINTERVALS[loadId] = setInterval(function() {
					var iframe = container.getElementsByTagName('iframe')[0]
					var cnx = container.querySelectorAll('.cnx-ui.cnx-ui-video')[0]
					
					if ((iframe && iframe.dataset.loadComplete === 'true') || cnx) {
						clearInterval(ADINTERVALS[loadId])
						container.setAttribute('data-ad-loaded', true)
					}
				}, 100)
				container.setAttribute('data-ad-loaded', true)
			}, 100)
		}
	},

	logAdId: function(slot, id) {
		if (!ADSLOADED.includes(slot)) {
			ADSLOADED.push(slot)
		}

		if ('adIds' in ADCONFIG[slot] === false) {
			ADCONFIG[slot]['adIds'] = []
		}

		if (!ADCONFIG[slot]['adIds'].includes(id)) {
			ADCONFIG[slot]['adIds'].push(id)
		}
	},

	injectAd: function(slot, container, markup) {
		var size = ISMOBILE ? 'mobile' : 'desktop'

		if (!markup) {
			container.setAttribute('data-ad-loaded', false)
		} else {
			var classes = [].slice.apply(container.classList)
			var filteredClasses = classes.filter(function(className) {
				var regex = /container__ad--(.*)/gm
				var m = regex.exec(className)
				while (m !== null) {
					if (m.index === regex.lastIndex) { regex.lastIndex++ }
					var potentialSize = m.length > 1 ? m[1] : null

					if (!potentialSize || !ADCONFIG.hasOwnProperty(potentialSize) || ADCONFIG[potentialSize].size.includes(size)) {
						break
					}

					return className
				}
			})

			container.setAttribute('data-ad-size', size)
			container.innerHTML = container.innerHTML + markup
			for (var i = 0; i < filteredClasses.length; i++) {
				container.classList.remove(filteredClasses[i])
			}

			if (slot == 'adhesion') this.addDismissButton(container)

			if (container.hasAttribute('data-ad-sticky')) {
				stickybits(container, {
					stickyBitStickyOffset: this.offset
				})
			}

			this.displayAd(slot, container)
		}
	},

	/**
	 *
	 *
	 * @param {*} container
	 */
	addDismissButton: function(container) {
		var dismissButton = document.createElement('DIV')

		dismissButton.className = 'ad__dismiss'
		container.appendChild(dismissButton)

		this.activateDismissButton()
	},

	/**
	 * Add event listener for dismiss button
	 */
	activateDismissButton: function() {
		var self = this
		var dismissButtons = document.querySelectorAll('.ad__dismiss')

		for (var i = 0; i < dismissButtons.length; i++) {
			var dismissButton = dismissButtons[i]
			dismissButton.addEventListener('click', function() {
				var container = this.parentElement
				var target = container.querySelector('.advertising-adslot').id

				container.setAttribute('data-ad-dismiss-adhesion', true)
				self.destroyAds('adhesion', target)
			})
		}
	},

	/**
	 *
	 * @param {array | string} slots - array or
	 * * string with slot names
	 * @param {*} target
	 */
	destroyAds: function(slots, target) {
		var destroy

		if (target == null) {
			slots = slots.constructor.name !== 'Array' ? [slots] : slots
			destroy = Object.keys(ADCODES).reduce(function(filtered, key) {
				for (var i = 0; i < slots.length; i++) {
					var slot = slots[i]
					if (ADCONFIG[slot]['adIds'].includes(key)) {
						filtered.push(ADCODES[key])
					}
					ADSLOADED = ADSLOADED.filter(function(target) {
						return slot != target
					})
				}

				return filtered
			}, [])
		} else {
			destroy = []
			if (ADCONFIG[slots]['adIds'].includes(target)) {
				destroy.push(ADCODES[target])
			}
		}

		googletag.destroySlots(destroy)
	}
}

module.exports = Advertisement
