import Gsap from 'gsap'

module.exports = function (el) {
  const animatedItems = el.querySelectorAll('.animated-item')
  const fadeItems = el.querySelectorAll('.fade-item')

  const animated = Gsap.to(animatedItems, {
    x: 0,
    y: 0,
    // rotation: 0,
    opacity: 1
  })

  animated.play()

  const fades = Gsap.to(fadeItems, {
    opacity: 1
  })

  fades.play()
}
