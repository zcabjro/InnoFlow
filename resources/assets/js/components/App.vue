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
                    <li><router-link to="#account">Account</router-link></li>
                    <li><router-link to="#sign-out">Sign out</router-link></li>
                  </ul>
                  <ul v-else class="dropdown-menu">
                    <li><router-link to="/login">Login</router-link></li>
                    <li><router-link to="/register">Register</router-link></li>
                  </ul>
                </li>
                <li v-if="auth" class="visible-xs" v-on:click="collapse"><router-link to="#account">Account</router-link></li>
                <li v-else class="visible-xs" v-on:click="collapse"><router-link to="/login">Login</router-link></li>
                <li v-if="auth" class="visible-xs" v-on:click="collapse"><router-link to="#sign-out">Sign out</router-link></li>
                <li v-else class="visible-xs" v-on:click="collapse"><router-link to="/register">Register</router-link></li>
            </ul>
          </div>
      </div>
    </div>
    
      
    <transition class="animated" appear name="fadeDown" mode="out-in">
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
    </transition>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        auth: false
      }
    },
    
    methods: {
      collapse(e) {
        //e.preventDefault();
        $('.collapse').collapse('hide');
      }
    }
  }
</script>

<style>
  #appNav {
    border-radius: 0;
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
