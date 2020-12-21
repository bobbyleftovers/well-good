import { initVueApp } from 'lib/init-vue'
import animateScrollTo from 'animated-scroll-to';

module.exports = function(el) {
initVueApp(el, {
    methods: {
      scrollTo(selector){
        animateScrollTo( document.querySelector(selector) , {
          maxDuration: 1000,
          minDuration: 250,
          verticalOffset: 0,
        });
        if(history.pushState) {
          history.pushState(null, null, selector);
        } else {
          location.hash = selector;
        }
      }
    }
  })
};
