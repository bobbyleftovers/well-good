const STICKY_OFFSET = window.innerWidth > 650 ? 250 : 80

class SponcerHeader {
  constructor (el) {
    this.el = el
    this.isSticky = false
    this.init()
  }

  init () {
    this.handleScroll = this.handleScroll.bind(this)
    $(window).on('scroll', this.handleScroll)
    this.handleScroll()
  }

  handleScroll () {
    const currentScroll = $(window).scrollTop()

    if (currentScroll > STICKY_OFFSET) {
      if (this.isSticky) return false
      this.isSticky = true
      $('body').addClass('sponcer-banner--sticky')
    } else {
      if (!this.isSticky) return
      this.isSticky = false
      $('body').removeClass('sponcer-banner--sticky')
    }
  }
}

module.exports = SponcerHeader
