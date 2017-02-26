<template>
  <!-- App container -->
  <div class="container-fluid">

    <!-- Nav bar -->
    <div id="appNav" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">

        <div class="navbar-header">
          <!-- InnoFlow brand -->
          <router-link to="/" class="navbar-brand">InnoFlow</router-link>
          
          <!-- Collapse button used on small devices -->
          <button id="collapseBtn" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#appNavCollapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="glyphicon glyphicon-user"></span>
                <span class="caret"></span>
          </button>
        </div>

        <!-- Navbar items (collapsed on small devices) -->
        <div id="appNavCollapse" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown hidden-xs">

              <!-- User icon -->
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-user"></span>
                <span class="caret"></span>
              </a>

              <!-- Authorised dropdown items -->
              <ul v-if="auth" class="dropdown-menu">
                <li>
                  <router-link to="dashboard">Dashboard</router-link>
                </li>
                <li>
                  <a v-on:click="logout" href="#">Sign out</a>
                </li>
              </ul>
              <!-- Unauthorised dropdown items -->
              <ul v-else class="dropdown-menu">
                <li>
                  <router-link to="login">Login</router-link>
                </li>
                <li>
                  <router-link to="register">Register</router-link>
                </li>
              </ul>
            </li>

            <!-- Authorised dropdown item (small devices) -->
            <li v-if="auth" class="visible-xs" v-on:click="collapse">
              <router-link to="dashboard">Dashboard</router-link>
            </li>
            <!-- Unauthorised dropdown item (small devices) -->
            <li v-else class="visible-xs" v-on:click="collapse">
              <router-link to="login">Login</router-link>
            </li>
            <!-- Authorised dropdown item (small devices) -->
            <li v-if="auth" class="visible-xs" v-on:click="collapse">
              <a v-on:click="logout" href="#">Sign out</a>
            </li>
            <!-- Unauthorised dropdown item (small devices) -->
            <li v-else class="visible-xs" v-on:click="collapse">
              <router-link to="register">Register</router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Nested route -->
    <div id="child">
      <transition class="animated" appear name="fadeDown" mode="out-in">
        <keep-alive>
          <router-view></router-view>
        </keep-alive>
      </transition>
    </div>
  </div>
</template>

<script>
  import bus from '../bus.js' // Global event bus

  export default {
    // Debug name and html tag of this component
    name: 'if-app',

    // Initialise app data with defaults
    data() {
      return {
        // Whether the user has been authorised or not
        auth: false
      }
    },

    // Called when the component is first created (note: not on navigation)
    created() {
      // Listen for the login event for updating the auth state
      bus.$on('login', this.isAuthorised);
      // Check the auth state in case we are already logged in
      this.checkAuth();
    },

    // App component methods
    methods: {
      // Collapses the navbar dropdown on small devices
      collapse(e) {
        $('.collapse').collapse('hide');
      },

      // TODO: Perform this check in a nicer way
      // Checks user auth state by contacting trying to access a restricted route
      checkAuth() {
        axios.get('/api/vsts')
          .then(this.isAuthorised)
          .catch(this.isNotAuthorised);
      },

      // Updates the auth state to be logged in
      isAuthorised() {
        this.auth = true;
      },

      // Updates the auth state to be logged out and tries to ensure we are at the landing page
      isNotAuthorised() {
        this.auth = false;
        this.$router.replace('/');
      },

      // Send GET request to logout API after confirmation
      logout(e) {
        e.preventDefault();
        if (confirm('Do you wish to sign out?')) {
          axios.get('/api/logout')
            .then(this.logoutSuccess)
            .catch(this.logoutFailure);
        }
      },

      // On success, emit the logout event and trigger auth update
      logoutSuccess(res) {
        console.log("Logout success");                
        bus.$emit('logout');
        this.isNotAuthorised();
      },

      // On failure, log the failure
      logoutFailure(error) {
        console.log('Logout failure');
      }
    }
  }
</script>

<style scoped>
  #appNav {
    border-radius: 0;
  }

  #child {
    margin-top: 50px;
  }
  
  #appNav .navbar-brand {
    font-size: 32px;
    color: white;
  }
  
  #collapseBtn {
    border: 0;
    margin-top: 0;
    margin-bottom: 0;
    padding-top: 14px;
    padding-bottom: 14px;
  }
  
  html {
    height: 100%;
  }
  
  body {
    height: 100%;
    padding-top: 50px;
  }
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity .2s
  }
  .fade-enter, .fade-leave-to {
    opacity: 0
  }
</style>