// https://github.com/callmecavs/layzr.js

import on from 'dom-event'
import { select, addClass, getData, setStyle, hasClass } from 'lib/dom'
import Layzr from 'layzr.js'
import throttle from 'lodash.throttle'

const wrapper = select('.wrapper')
const body = document.body
const LOADED_CLASS = 'image--loaded'
const DELAY_TIMING = 100

const instance = window.layzr = Layzr({
  threshold: DELAY_TIMING
})

const doesSupportObjectFit = () => {
  const i = document.createElement('img')
  return ('objectFit' in i.style)
}

const objectFit = doesSupportObjectFit()
if (!objectFit) addClass('no-object-fit', body)

instance
  .on('src:before', image => {
    on(image, 'load', (event) => {
      const imageWrapper = image.parentNode
      addClass(LOADED_CLASS, imageWrapper)
    })
  })

instance
  .on('src:after', el => {
    const imageWrapper = el.parentNode
    if (!hasClass('js-wrap', imageWrapper)) return
    if (!objectFit) {
      const src = getData('normal', el)
      setStyle('backgroundImage', 'url("' + src + '")', imageWrapper)
      addClass(LOADED_CLASS, imageWrapper)
    }
  })

const updateLazyLoad = () => instance.update().check()

updateLazyLoad().handlers(true)

if (wrapper) {
  on(wrapper, 'scroll', throttle(updateLazyLoad, DELAY_TIMING))
} else {
  on(window, 'scroll', throttle(updateLazyLoad, DELAY_TIMING))
}

export default (el, payload = {}) => {
  if (!payload.inside_vue) return
  if (select('img', el).getAttribute('srcset')) {
    addClass(LOADED_CLASS, el)
  }
  setTimeout(() => {
    instance
      .update().check()
  }, 0)
}

export {
  updateLazyLoad
}
