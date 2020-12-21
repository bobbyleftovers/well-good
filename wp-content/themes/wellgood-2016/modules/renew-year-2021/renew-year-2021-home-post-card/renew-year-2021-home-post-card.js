
import { ScrollTrigger } from 'lib/gsap-scroll-plugin'

module.exports = function (el) {
  this.hasAnimated = false
  this.$el = el

  this.show = () => {
    if (this.hasAnimated) return
    this.hasAnimated = true
    this.$el.classList.add('show')
  }

  this.attachScroll = () => {
    ScrollTrigger.create({
      trigger: this.$el,
      start: () => `top bottom`,
      markers: false,
      onUpdate: ({progress}) => {
        if (progress >= 0.3) this.show()
      }
    })
  }

  this.attachScroll()
}
