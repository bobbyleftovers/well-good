import Vue from 'vue'
import Sitemap from './components/Sitemap.vue'
import './scss/main.scss'

Vue.config.productionTip = false

document.addEventListener('DOMContentLoaded', () => {
  return new Vue({
    // router,
    render: h => h(Sitemap)
  }).$mount('.wg-redirections-sitemap')
})
