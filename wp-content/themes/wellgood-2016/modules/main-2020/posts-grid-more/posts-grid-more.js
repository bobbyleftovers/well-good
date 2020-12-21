import { initVueApp } from 'lib/init-vue'

module.exports = function(el){
  initVueApp(el, {
    data(){
      return {
        posts: {}
      }
    }
  })
}
