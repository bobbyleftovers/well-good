
import Gsap from "gsap";
import select from 'select-dom'
import { ScrollTrigger } from 'lib/gsap-scroll-plugin'
import { onViewportSafeResize, setHeightElCss } from 'lib/viewport'

module.exports = function(el){

  // Get unique id
  this.uid = (id) => {
    id = `id${id}`
    if(typeof this[id] !== 'undefined') return this[id]
    if(typeof window.__scrollTriggerCounter__ === 'undefined') window.__scrollTriggerCounter__ = -1;
    window.__scrollTriggerCounter__ ++;
    this[id] = 'gsap-scroll-trigger-'+window.__scrollTriggerCounter__
    return this[id]
  }

  // Select DOM nodes
  this.select = selector => {
    if(typeof this[selector] !== 'undefined') return this[selector]
    this[selector] = select(`.trends-2021-spotlight__${selector}`, this.$el)
    return this[selector]
  }
  
  // Fix heading margin bottom
  this.fixHeading = () => {
    setHeightElCss('.trends-2021-spotlight__content', 0.485, 'paddingTop')
    Gsap.set(this.select('heading'), {
      marginBottom: -this.select('heading').getBoundingClientRect().height/2
    })
  }

  this.loadImage = () => {
    const img = this.select('img__bg')
    const src = img.dataset.src
    var loadimg = new Image;
    loadimg.onload = () => {
      img.style.backgroundImage = `url(${src})`
      img.dataset.src = null
      img.classList.remove('bg-white')
      img.classList.remove('opacity-30')
    }
    loadimg.src = src;
  }

  this.loadImageOnScroll = () => {
    new ScrollTrigger({
      trigger: this.select('trigger'),
      start: () => `top 200%`,
      id: this.uid(99),
      onEnter: () => {
        this.killScrollTriggerById(99)
        this.loadImage()
      }
    })
  }

  // Image anmiation and content pinning
  this.startTween = () => {
    const relativeDuration = 0.7
    this.tween = Gsap.to(this.select('img'), {
      scrollTrigger: {
        id: this.uid(1),
        trigger: this.select('trigger'),
        start: () => `top ${select('.header__inner').getBoundingClientRect().height}px`,
        end: () => `+=${this.select('img').getBoundingClientRect().height * relativeDuration}`,
        pin: this.select('content'),
        onLeave: () => this.$el.classList.add('show-content'),
        onEnterBack: () => this.$el.classList.remove('show-content'),
        pinSpacing: true,
        onRefresh: ({progress}) => {
          (progress >= 1) ? this.$el.classList.add('show-content') : this.$el.classList.remove('show-content')
        },
        scrub: 0.9,
      },
      force3D: true,
      yPercent: -(100-relativeDuration*100)
    })
  }

  this.killScrollTriggerById = (id) => {
    var scrollTrigger = ScrollTrigger.getById(this.uid(id))
    if(scrollTrigger) scrollTrigger.kill()
  }

  // Kill
  this.kill = () => {
    this.killScrollTriggerById(1)
    this.tween.kill()
    Gsap.set(this.select('img'), {clearProps: 'opacity'})
    Gsap.set(this.select('img-wrapper'), {clearProps: true})
    Gsap.set(this.select('content'), {clearProps: true})
  }

  // On Resize
  this.resize = () => {
    this.show(false)
    clearTimeout(this.spotlightTimeout)
    this.spotlightTimeout = setTimeout(()=>{
      this.kill()
      clearTimeout(this.spotlightTimeoutInit)
      this.spotlightTimeoutInit = setTimeout(this.mount, 50)
    }, 50)
  }

  // Show/Hide
  this.show = (show = true) => {
    if(!show) return this.$el.classList.remove('show')
    clearTimeout(this.spotlightTimeoutShow)
    this.spotlightTimeoutShow = setTimeout(() => this.$el.classList.add('show'), 50)
  }

  // Attach events
  this.attachEvents = () => {
    if(typeof this.eventsAttached !== 'undefined' && this.eventsAttached) return
    onViewportSafeResize(this.resize)
    this.eventsAttached = true
  }

  // Mount spotlight
  this.mount = () => {
    this.$el = el
    if(!this.select('img')){
      this.show()
      this.$el.classList.add('show-content')
      return;
    }
    setTimeout(() => {
      this.fixHeading()
      this.startTween()
      this.attachEvents()
      this.show()
      this.loadImageOnScroll()
    }, 0)
  }

  // Init
  this.mount()
}