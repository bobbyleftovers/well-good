import { initVueApp } from 'lib/init-vue'
import animateScrollTo from 'animated-scroll-to';

module.exports = (el) => initVueApp(el, {
    el: el,
    data: function(){
      return {
        currentRoute: 'Typography'
      }
    },
    methods: {
      toRoute(route){
        animateScrollTo(0,{
          maxDuration: 100,
        });
        this.currentRoute = route
      },
      scrollTo(selector){
        animateScrollTo( document.querySelector(selector) , {
          maxDuration: 1000,
          minDuration: 250,
          verticalOffset: -80,
        });
      }
    },
    mounted(){
      if(window.location.hash) this.currentRoute = window.location.hash.substr(1).replace(/%20/g," ")
    }
  });
