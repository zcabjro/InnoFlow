<template>>
  <!-- Login container -->
  <div id="login" class="container">
    
    <!-- UserForm component -->
    <if-user-form :legend="legend" :fields="[email, password]"></if-user-form>

    <div class="form-group">
      <!-- Login button -->
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="login" type="button" class="btn">Login</button>
      </div>

      <br><br>

      <!-- Register link -->
      <div class="col-md-4 col-md-offset-4">
        <p>Need an account?
          <router-link to="/register">Register</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import IfUserForm from './IfUserForm.vue' // Form used for supplying login info
  import bus from '../bus.js' // Global event bus

  // Helper for resetting login data
  function defaultLoginData() {
    return {
      // Title of the form
      legend: 'Login',
      // Email field
      email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '' },
      // Password field
      password: { label: 'Password', type: 'password', placeholder: 'password', value: '' }
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-login',

    // Components used by this component
    components: {
      IfUserForm
    },

    // Initialise login data with defaults
    data() {
      return defaultLoginData();
    },

    // Reset login data each time we navigate to the login route
    beforeRouteEnter(to, from, next) {
      next(loginComponent => {
        loginComponent.resetLoginData();
      });
    },

    // Login component methods
    methods: {
      // Resets the login data
      resetLoginData() {
        Object.assign(this.$data, defaultLoginData());
      },

      // Send POST request to login API, supplying email and password
      login(e) {
          axios.post('/api/login', { email: this.email.value, password: this.password.value })
          .then(this.loginSuccess)
          .catch(this.loginFailure);
      },

      // On succes, emit the login event and navigate to dashboard
      loginSuccess(res) {
        console.log("Login success");
        bus.$emit('login');
        this.$router.push('/dashboard');
      },
      
      // On failure, log the failure
      loginFailure(error) {
        console.log("Login failure");
      }      
    }
  }
</script>

<style></style>