import Vue from 'vue'
import VueRouter from 'vue-router'

// Components
import App from './components/App.vue'
import Landing from './components/Landing.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import Dashboard from './components/Dashboard.vue'

Vue.use(VueRouter)

const routes = [{

  // Root
  path: '/',
  component: App,

  // Nested routes
  children: [{

    // Landing page
    path: '',
    component: Landing
  }, {

    // Registration
    path: 'register',
    component: Register
  }, {
    
    // Login
    path: 'login',
    component: Login
  }, {
    
    // Dashboard
    path: 'dashboard',
    component: Dashboard
  }]
}, {
  // Redirect fallback
  path: '*',
  redirect: '/'
}]

export var router = new VueRouter({
  routes
})
