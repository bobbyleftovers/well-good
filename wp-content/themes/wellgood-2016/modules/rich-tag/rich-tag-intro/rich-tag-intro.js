import { initVueApp } from 'lib/init-vue'
import animateScrollTo from 'animated-scroll-to'
import { waitForModules } from 'lib/init-modules'

module.exports = el => waitForModules(['trending-posts'], () => {
  initVueApp(el, {
      components: {trendingPosts: window.BRRL_VUE_COMPONENTS['trending-posts']},
      methods: {
        scrollTo(selector){
          animateScrollTo( document.querySelector(selector) , {
            maxDuration: 1000,
            minDuration: 250,
            verticalOffset: -20,
          });
        }
      }
    })
});
