
import Gsap from 'gsap'
import { ScrollTrigger } from 'lib/gsap-scroll-plugin'
import select from 'select-dom'

module.exports = function(el) {
  
  // Vars
  this.controller = { x: 0, y: 0, relative: true }
  this.top = el.querySelector('.trends-2021-post-card--top')
  this.bottom = el.querySelector('.trends-2021-post-card--bottom')
  this.middle = el.querySelector('.trends-2021-post-card--middle')
  this.middleYSetter = Gsap.quickSetter(this.middle, "y", "%")
  this.topYSetter = Gsap.quickSetter(this.top, "y", "%")
  this.rect = el.getBoundingClientRect()
  this.needsNewBoundingClientRect = false
  this.mouseParallaxAmount = parseInt(el.dataset.mouseParallaxAmount)
  this.parallaxTimeout = null
  this.scrollGsap = null
  this.loadedImages = []
  this.isLoading = false
  this.isLoaded = false
  this.callback = false
  this.quickSetter = val => {
    this.middleYSetter(val)
    this.topYSetter(val * 2)
  }

  this.loadImages = (callback = false) => {
    if(callback) this.callback = callback
    if(this.isLoaded && this.callback) return this.callback()
    const checkIfLoaded = (id = false) => {
      if(id) this.loadedImages.push(id)
      if( this.loadedImages.includes("top") &&  this.loadedImages.includes("bottom")) {
        if(this.callback) this.callback();
        this.isLoaded = true
        this.isLoading = false
        return true
      }
      return false
    }
    const loadImage = (id) => {
      const img = select('img', this[id])
      img.onload = () => checkIfLoaded(id)
      img.src = img.dataset.src;
    }
    checkIfLoaded()
    if(!this.isLoaded && !this.isLoading){
      this.isLoading = true
      loadImage('top')
      loadImage('bottom')
    } else if(this.isLoaded && this.callback){
      this.callback()
    }
  }

  this.getBoundingClientRect = () => {
    this.rect = el.getBoundingClientRect()
  }

  this.hasTriggered = false;

  this.addScrollAppear = () => {
    ScrollTrigger.create({
      trigger: el,
      onUpdate: ({progress}) => {
        if(!this.hasTriggered && progress > 0.1) this.loadImages(() => setTimeout(() => el.classList.add('show'), 10))
      }
    })
  }

  this.resize = () => {
    const width = this.top.getBoundingClientRect().width
    const borderRadius = width/2 + 'px'
    this.top.style.borderTopLeftRadius = borderRadius
    this.top.style.borderTopRightRadius = borderRadius
    this.bottom.style.borderTopLeftRadius = borderRadius
    this.bottom.style.borderTopRightRadius = borderRadius
  }

  //Resize
  this.resize()
  window.addEventListener('resize', this.resize)

  //Preload 
  if (parseInt(el.dataset.preload) == 1) this.loadImages()

  // Appear on scroll
  this.addScrollAppear();
}
