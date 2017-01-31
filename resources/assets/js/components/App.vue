<template>
  <div class="container-fluid">
    <!-- Nav bar -->
    <div id="appNav" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <router-link to="/" class="navbar-brand">InnoFlow</router-link>
          <button id="collapseBtn" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#appNavCollapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="glyphicon glyphicon-user"></span>
                <span class="caret"></span>
              </button>
        </div>
        <div id="appNavCollapse" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown hidden-xs">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-user"></span>
                <span class="caret"></span>
              </a>
              <ul v-if="auth" class="dropdown-menu">
                <li>
                  <router-link to="dashboard">Dashboard</router-link>
                </li>
                <li>
                  <a v-on:click="logout" href="#">Sign out</a>
                </li>
              </ul>
              <ul v-else class="dropdown-menu">
                <li>
                  <router-link to="login">Login</router-link>
                </li>
                <li>
                  <router-link to="register">Register</router-link>
                </li>
              </ul>
            </li>
            <li v-if="auth" class="visible-xs" v-on:click="collapse">
              <router-link to="dashboard">Dashboard</router-link>
            </li>
            <li v-else class="visible-xs" v-on:click="collapse">
              <router-link to="login">Login</router-link>
            </li>
            <li v-if="auth" class="visible-xs" v-on:click="collapse">
              <a v-on:click="logout" href="#">Sign out</a>
            </li>
            <li v-else class="visible-xs" v-on:click="collapse">
              <router-link to="register">Register</router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>

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
  import bus from '../bus.js'

  export default {
    name: 'app',

    data() {
      return {
        auth: false
      }
    },

    created() {
      bus.$on('login', this.isAuthorised);
      this.checkAuth();
    },

    // beforeRouteEnter(to, from, next) {
    //   next(vm => {
    //     vm.checkAuth();
    //   });
    // },

    methods: {
      collapse(e) {
        $('.collapse').collapse('hide');
      },

      checkAuth() {
        // TEMP: using token route to establish whether or not user is logged in
        axios.get('/api/token')
          .then(this.isAuthorised)
          .catch(this.isNotAuthorised);
      },

      isAuthorised() {
        this.auth = true;
        console.log('isAuth');
      },

      isNotAuthorised() {
        this.auth = false;
        this.$router.push('/');
        console.log('isNotAuth');
      },

      logout(e) {
        e.preventDefault();
        if (confirm('Do you wish to sign out?')) {
          axios.get('/api/logout')
            .then(this.logoutSuccess)
            .catch(this.logoutFailure);
        }
      },

      logoutSuccess(res) {
        this.auth = false;
        this.$router.replace('/');
        bus.$emit('logout');
        console.log('logged out');
      },

      logoutFailure(error) {
        console.log('Failed to logout!');
      }
    }
  }
</script>

<style>
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