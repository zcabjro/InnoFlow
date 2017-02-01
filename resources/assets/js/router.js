import Vue from 'vue'
import VueRouter from 'vue-router'

// Components
import IfApp from './components/IfApp.vue'
import IfLanding from './components/IfLanding.vue'
import IfRegister from './components/IfRegister.vue'
import IfLogin from './components/IfLogin.vue'
import IfDashboard from './components/IfDashboard.vue'
import IfCreateClass from './components/IfCreateClass.vue'

Vue.use(VueRouter)

const routes = [{

  // Root
  path: '/',
  component: IfApp,

  // Nested routes
  children: [{

    // Landing page
    path: '',
    component: IfLanding
  }, {

    // Registration
    path: 'register',
    component: IfRegister
  }, {
    
    // Login
    path: 'login',
    component: IfLogin
  }, {
    
    // Dashboard
    path: 'dashboard',
    component: IfDashboard
  }, {

    // Create class
    path: 'create',
    component: IfCreateClass
  }]
}, {
  // Redirect fallback
  path: '*',
  redirect: '/'
}]

export var router = new VueRouter({
  routes
})
