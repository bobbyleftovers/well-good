
import { ScrollTrigger } from 'lib/gsap-scroll-plugin'
import select from 'select-dom'

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
      pin: select('.pin', this.$el),
      start: () => `top ${select('.header__inner').getBoundingClientRect().height}px`,
      end: () => `bottom bottom`,
      markers: false, // set this to false
      onUpdate: ({progress}) => {
        if (progress >= 0.3) this.show()
      }
    })
  }

  setTimeout(this.attachScroll, 100)
}
