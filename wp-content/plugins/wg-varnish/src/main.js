import Vue from 'vue'
import App from './App.vue'
// import router from './router'
import './scss/main.scss'
import Loading from '@/components/Loading'

Vue.component('Loading', Loading)

Vue.config.productionTip = false

document.addEventListener('DOMContentLoaded', () => {
  new Vue({
    // router,
    render: h => h(App)
  }).$mount('.wg-varnish-admin-page__content')
})
