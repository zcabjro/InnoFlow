<template>>
  <!-- Login container -->
  <div id="login" class="container">
    
    <!-- Message component -->
    <if-message ref="message"></if-message>

    <!-- UserForm component -->
    <if-user-form :legend="legend" :fields="fields"></if-user-form>

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
  import IfMessage from './IfMessage.vue' // Message copmonent for displaying errors
  import HttpErrorHelper from '../httpErrorHelper.js' // Error processor for http requests

  // Helper for resetting login data
  function defaultLoginData() {
    return {
      // Title of the form
      legend: 'Login',
      // Email field
      email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '', format: function(val) { return val.trim(); } },
      // Password field
      password: { label: 'Password', type: 'password', placeholder: 'password', value: '', format: function(val) { return val.trim(); } }
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-login',

    // Components used by this component
    components: {
      IfUserForm,
      IfMessage
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

    computed: {
      fields() {
        return [ this.email, this.password ];
      }     
    },

    // Login component methods
    methods: {
      // Resets the login data
      resetLoginData() {
        Object.assign(this.$data, defaultLoginData());
      },

      // Send POST request to login API, supplying email and password
      login(e) {        
        if (this.validFields()) {
          let loginDetails = { email: this.email.value, password: this.password.value };
          axios.post('/api/login', loginDetails)
            .then(this.loginSuccess)
            .catch(this.loginFailure);
        }
      },

      // Check fields are valid and display message if not
      validFields() {        
        if (!this.email.value) {          
          this.$refs.message.display('Please enter an email address.');
          return false;
        }
        if (!this.password.value) {
          this.$refs.message.display('Please enter a password.');
          return false;
        }
        return true;
      },

      // On succes, emit the login event and navigate to dashboard
      loginSuccess(res) {
        console.log("Login success");
        bus.$emit('login');
        this.$router.push('/dashboard');
      },
      
      // On failure, log the failure
      loginFailure(error) {        
        this.$refs.message.display(HttpErrorHelper(error).payload);
        console.log("Login failure");
      }
    }
  }
</script>

<style></style>