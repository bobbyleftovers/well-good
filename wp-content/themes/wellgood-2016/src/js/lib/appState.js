import Vue from 'vue';

const appState = {
  emailCapture: null,
}

window._populateMarketingSlider = (payload = null) => {
  let finalPayload = payload
  if (!payload) {
    if (window.__EMAIL_CAPTURE__PAYLOAD__) {
      finalPayload = window.__EMAIL_CAPTURE__PAYLOAD__
    } else {
      return null
    }
  }
  Vue.set(appState, 'emailCapture', finalPayload)
  console.log('populateMarketingSlider', finalPayload)
}

window._populateMarketingSlider()

export default appState;

if(typeof window.__BUS__ === 'undefined') window.__BUS__ = new Vue()

export const bus = window.__BUS__
