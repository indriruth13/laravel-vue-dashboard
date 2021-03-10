import Vue from 'vue'
import vuetify from '../plugins/vuetify'
import Login from './components/Login'
import App from './components/App'
import Dashboard from './components/Dashboard'
import VueRouter from 'vue-router'
import VueDragDrop from 'vue-drag-drop'

Vue.use(VueRouter)
Vue.use(VueDragDrop)

const routes = [
  {
    path: '/',
    component: Login
  },
  {
    path: '/dashboard',
    component: Dashboard
  }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  vuetify,
  router,
  el: '#app',
});
