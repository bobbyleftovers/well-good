import { initVueApp } from 'lib/init-vue'
import { waitForModules } from 'lib/init-modules'

module.exports =  el => waitForModules(['trending-posts'], () => {
  initVueApp(el)
});
