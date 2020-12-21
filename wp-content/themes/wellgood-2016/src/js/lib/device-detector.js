import isTouchDevice from 'is-touch-device'
import select from 'select-dom'

export const isTouch = isTouchDevice()

// Opera 8.0+
export const isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

// Firefox 1.0+
export const isFirefox = typeof InstallTrigger !== 'undefined';

// Safari 3.0+ "[object HTMLElementConstructor]" 
export const isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification));

// Internet Explorer 6-11
export const isIE = /*@cc_on!@*/false || !!document.documentMode;

// Edge 20+
export const isEdge = !isIE && !!window.StyleMedia;

// Chrome 1 - 79
export const isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

// Edge (based on chromium) detection
export const isEdgeChromium = isChrome && (navigator.userAgent.indexOf("Edg") != -1);

// Blink engine detection
export const isBlink = (isChrome || isOpera) && !!window.CSS;

const $html = select('html')

const addClass = className => {
  $html.classList.add(className)
}

if(isTouch) {
  addClass('is-touch')
} else {
  addClass('no-touch')
}

if(isOpera) {
  addClass('is-opera')
} else {
  addClass('no-opera')
}

if(isFirefox) {
  addClass('is-firefox')
} else {
  addClass('no-firefox')
}

if(isSafari) {
  addClass('is-safari')
} else {
  addClass('no-safari')
}

if(isIE) {
  addClass('is-ie')
} else {
  addClass('no-ie')
}

if(isEdge) {
  addClass('is-edge')
} else {
  addClass('no-edge')
}

if(isChrome) {
  addClass('is-chrome')
} else {
  addClass('no-chrome')
}

if(isEdgeChromium) {
  addClass('is-edge-chromium')
} else {
  addClass('no-edge-chromium')
}

if(isBlink) {
  addClass('is-blink')
} else {
  addClass('no-blink')
}

