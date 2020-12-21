import Vue from 'vue'
import Importer from './components/Importer.vue'
import './scss/main.scss'

Vue.config.productionTip = false

document.addEventListener('DOMContentLoaded', () => {
  return new Vue({
    // router,
    render: h => h(Importer)
  }).$mount('.wg-redirections-importer')
})
