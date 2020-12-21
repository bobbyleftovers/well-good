import select from 'select-dom'
import { isIE } from 'lib/device-detector'

module.exports = function($el) {

  const DURATION = 1.2

  const $wrapper = select('.trends-2021-home-hero__bg-wrapper', $el)
  const $canvas = select('canvas', $wrapper)
  var $img = select('img', $wrapper)

  var width = 0
  var height = 0
  var duration = DURATION * 1000
  var progress = 0
  var ctx = $canvas.getContext('2d')
  var startTime = 0
  var totalTime = 0
  var status = 0
  this.breakpoint = false

  const loadImage = (src, callback = () =>{}, img = false) => {
    if(!img) img = new Image;
    img.onload = callback
    img.src = src;
  }

  const fetchImage = (callback = () =>{}) => {
    if(this.breakpoint === 'lg'){
      var imageData = $el.dataset.imageLg
    } else {
      var imageData = $el.dataset.imageXs
    }
    loadImage(imageData, callback, select('.trends-2021-home-hero__bg-img', $el))
  }

  const animate = () => {
    var time = new Date().getTime()
    if(!startTime) startTime = time
    totalTime = time - startTime
    progress = 100/duration * totalTime / 100
    draw(progress)
    if(progress > 0.3 && status < 1) {
      $el.classList.add('show')
      status++
    }
    if(progress < 1) window.requestAnimationFrame(animate)
    else end()
  }

  const draw = (progress = 0) => {
    ctx.clearRect(0, 0, width, height)
    ctx.save()
    ctx.ellipse(0, height*0.4, width * progress, height * 1.1 * progress, 0, 0, 2 * Math.PI)
    ctx.ellipse(width, height*0.4, width * progress, height * 1.1 * progress, 0, 0, 2 * Math.PI)
    ctx.clip()
    ctx.drawImage($img, 0, 0, width, height)
    ctx.restore()
  }

  const onViewportResize = () => {
    var prevBrekpoint = this.breakpoint
    if(window.innerWidth > 480){
      this.breakpoint = 'lg'
    } else {
      this.breakpoint = 'xs'
    }
    if(prevBrekpoint && prevBrekpoint != this.breakpoint) init()
  }

  const resize = (draw = true) => {
    const boundingBox = $wrapper.getBoundingClientRect()
    width = parseInt(boundingBox.width)
    height = parseInt(boundingBox.height)
    $canvas.width = width
    $canvas.height = height
    if(draw) draw()
  }

  const IE = () => {
    fetchImage(() => {
      $el.classList.add('start')
      $el.classList.add('show')
      $el.classList.add('end')
    })
  }

  const nonIE = () => {
    resize(false)
    fetchImage(() => {
      draw()
      $el.classList.add('start')
      animate()
    })
  }

  const init = () => {
    onViewportResize()
    if(isIE){
      IE()
    } else {
      nonIE()
    }
    window.addEventListener('resize', onViewportResize)
  }

  const end = () => {
    $el.classList.add('end')
    setTimeout(() => {
      $canvas.style.display = 'none'
    }, 1000)
  }

  setTimeout(init, 50)
}