import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'CustomPurge',
    component: () => import(/* webpackChunkName: "CustomPurge" */ '../views/CustomPurge.vue')
  }
]

const router = new VueRouter({
  routes
})

export default router
