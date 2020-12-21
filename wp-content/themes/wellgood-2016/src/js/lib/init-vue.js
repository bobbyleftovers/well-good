import Vue from 'vue'
if(typeof window.BRRL_VUE_COMPONENTS === 'undefined') window.BRRL_VUE_COMPONENTS = [];

const vueAppMixin = {
  beforeCreate: function () {

  },
  mounted: function() {
    /* select.all('[data-module-init]:not([data-module-fired])',this.$el).forEach(function(internalModule){
      console.log(internalModule);
    }) */
  }
}


function prepareComponent(el, app){

  if(typeof app.mixins != 'undefined') app.mixins = Object.assign([], [vueAppMixin], app.mixins)
  else app.mixins = [vueAppMixin]
  return app;

}


module.exports.initVueApp = function(el, app) {
  app.el = el
  return setTimeout(function(){
    new Vue(prepareComponent(el, app))
  }, 0);
};


module.exports.registerVueComponent = function(el, component){
  component.template = el;
  window.BRRL_VUE_COMPONENTS[el.dataset.moduleInit] = Vue.component(el.dataset.moduleInit, prepareComponent(el, component))
}
