import Vue from 'vue'
import Maintenance from './components/Maintenance.vue'
import './scss/main.scss'

Vue.config.productionTip = false

document.addEventListener('DOMContentLoaded', () => {
  return new Vue({
    // router,
    render: h => h(Maintenance)
  }).$mount('.wg-redirections-maintenance')
})
