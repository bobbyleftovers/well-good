import { isTouch } from './device-detector'
import select from 'select-dom'
import { bus } from 'lib/appState'

var orientation = null
var $html = select('html')
var $header = select('.header__inner')

export const setOrientation = () => {
  if(window.innerHeight > window.innerWidth) var newOrientation = 'portrait'
  else newOrientation = 'landscape'
  if(newOrientation != orientation) {
    $html.classList.remove('orientation-'+orientation)
    orientation = newOrientation
    $html.classList.add('orientation-'+orientation)
    bus.$emit('viewport-change-orientation', orientation)
    return true
  }
  return false
}

export const setHeightElCss = (selector, viewportHeight = 1, property = 'height') => {
  select.all(selector).forEach(el => {
    el.style[property] = window.innerHeight * viewportHeight - $header.getBoundingClientRect().height + 'px'
  })
}

export const onViewportSafeResize = (fn) => {
  if(isTouch) bus.$on('viewport-change-orientation', fn)
  else window.addEventListener("resize", fn)
}

const onChangeOrientation = () => {
  if(isTouch){
    setHeightElCss('.h-viewport', 1, 'height')
    setHeightElCss('.min-h-viewport', 1, 'minHeight')
    setHeightElCss('.max-h-viewport', 1, 'maxHeight')
  }
}

const onResize = () => {
  if(setOrientation()) onChangeOrientation()
}

window.addEventListener("resize", onResize)
onResize()