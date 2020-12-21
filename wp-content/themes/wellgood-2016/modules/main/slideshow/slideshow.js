/**
 * Populate the slider__meta element with data on slide change
 * @constructor
 * @param {Object} el - The slider__meta element.
 */
const $ = require('jquery')
const History = require('history')
const firePageView = require('@modules/main/fire-page-view/fire-page-view')

function Slideshow(el) {
  this.el = el
  this.slides = document.querySelectorAll('.slider__slide')
  this.baseUrl = this.el.dataset.slideshowUrl
  this.metaContainer = document.querySelector('.js-slide-data-container')

  this.currentIndex = 0

  this.initSlideShow()
}

Slideshow.prototype = {

  /*
   * Get all elements with the data-slide-meta attribute.
   * Fill the elements with an entry data from the data obj. according to data-slide-meta.
   *
   * Two exceptions: cta_btn and slide_text are more complex elements
   * and are handled conditionally within the loop.
   */
  insertSlideData: function(data) {
    const elements = this.metaContainer.querySelectorAll('[data-slide-meta]')
    const hasCTA = data.cta_link === '' ? false : true
    const hasSponsor = data.sponsor.name === '' ? false : true

    elements.forEach((element) => {
      const item = element.dataset.slideMeta
      let entry = data[item]

      this.applyGutterClass(hasCTA)

      switch (true) {
        case item === 'cta_btn':
          element.setAttribute('href', data.cta_link)
          element.innerHTML = data.cta_text
          this.handleFooterElements(element, hasCTA)
          break

        case item === 'sponsor_text':
          entry = `${data.sponsor.label} ${data.sponsor.name}`
          element.innerHTML = entry
          this.handleFooterElements(element, hasSponsor)
          break

        case item === 'sponsor.link':
          element.setAttribute('href', data.sponsor.link)
          break

        case item === 'sponsor.logo_url':
          element.setAttribute('src', data.sponsor.logo_url)
          break

        default:
          element.innerHTML = entry
      }
    })
  },

  handleFooterElements: function(element, condition) {
    const parent = element.parentNode
    if (condition) {
      parent.classList.remove('js-is-hidden')
    } else {
      parent.classList.add('js-is-hidden')
    }
  },

  applyGutterClass: function(condition) {
    const sponsor = this.metaContainer.querySelector('.slide__sponsor')
    if (!sponsor) {
      return
    }

    if (condition) {
      sponsor.classList.add('js-add-gutter')
    } else {
      sponsor.classList.remove('js-add-gutter')
    }
  },

  updateSlideshowUrl: function(index) {
    const slide = index + 1

    History.replaceState({}, document.title, this.baseUrl + '/' + slide)

    // Track event in GTM
    if (dataLayer) {
      dataLayer.push({
        'event': 'VirtualPageview',
        'slide': slide,
        'slides': this.slides.length,
      })
    }
    firePageView()
  },

  initSlideShow: function() {
    new Flickity(this.el, {
      arrowShape: 'M24.5,49.8h52.9 M38.1,34.3L22.6,49.8l16,16',
      on: {
        ready: () => {
          const activeSlide = this.slides[0]
          const slideData = JSON.parse(activeSlide.dataset.slideData)

          this.insertSlideData(slideData)
        },
        change: index => {
          const activeSlide = this.slides[index]
          const slideData = JSON.parse(activeSlide.dataset.slideData)

          this.currentIndex = index
          this.insertSlideData(slideData)
          this.updateSlideshowUrl(index)
        }
      }
    })
  }
}

module.exports = Slideshow
