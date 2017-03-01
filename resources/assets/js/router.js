import Vue from 'vue'
import VueRouter from 'vue-router'

// Components
import IfApp from './components/IfApp.vue'
import IfLanding from './components/IfLanding.vue'
import IfRegister from './components/IfRegister.vue'
import IfLogin from './components/IfLogin.vue'
import IfDashboard from './components/IfDashboard.vue'
import IfCreateClass from './components/IfCreateClass.vue'
import IfEnrolProject from './components/IfEnrolProject.vue'
import IfInnovations from './components/IfInnovations.vue'
import IfProject from './components/IfProject.vue'
import IfClass from './components/IfClass.vue'

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
    redirect: 'dashboard/innovations',
    component: IfDashboard,    
    children: [{

      // Innovations page
      path: 'innovations',
      component: IfInnovations
    },{

      // Projects
      path: 'projects/:id',
      component: IfProject
    }, {

      // Classes
      path: 'classes/:id',
      component: IfClass
    }]
  }, {

    // Create class
    path: 'create',
    component: IfCreateClass
  }, {

    // Enroll project
    path: 'enrol',
    component: IfEnrolProject // TODO: Change to IfEnrolProject once it is created
  }]
},  {
  path: '/notFound'
}, {
  // Redirect fallback
  path: '*',
  redirect: '/notFound'
}]

export var router = new VueRouter({
  routes
})
