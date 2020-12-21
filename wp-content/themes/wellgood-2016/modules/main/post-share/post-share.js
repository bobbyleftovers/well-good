var stickybits = require('stickybits/dist/stickybits')

function PostShare(el) {
  this.header = document.querySelector('.header')
  this.sponsorBanner = document.querySelector('.post-hero__sponsor-banner')
  this.postShare = el
  this.isSticky = true
  this.stickyBreakpoint = 641

  this.events()
  this.setup()
  this.stick()
}

PostShare.prototype = {
  events: function() {
    window.addEventListener('resize', () => {
      this.stick()
    })
  },

  setup: function() {
    this.calculateOffset()

    this.postShareSticky = stickybits(this.postShare, {
      stickyBitStickyOffset: this.offset
    })
  },

  stick: function() {
    if (window.innerWidth < this.stickyBreakpoint){
      if (this.isSticky) {
        this.postShareSticky.cleanup()
      }

      this.isSticky = false
    } else {
      if (!this.isSticky) {
        this.setup()
      }

      this.isSticky = true
    }
  },

  calculateOffset: function() {
    this.offset = 20

    if (this.header) {
      this.offset += this.header.offsetHeight
    }
    if (this.sponsorBanner) {
      this.offset += this.sponsorBanner.offsetHeight
    }
  }
}


module.exports = PostShare
