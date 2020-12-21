import Vue from 'vue'
require('./email-capture.vue')

var isEmailCaptureLoaded = false;

module.exports = el => {

  if (isEmailCaptureLoaded) {
    return;
  }

  new Vue({ el: el });

  isEmailCaptureLoaded = true;
}