<template>
  <!-- Register container -->
  <div id="register" class="container">

    <!-- Message component -->
    <if-message ref="message"></if-message>

    <!-- UserForm component -->
    <if-user-form :legend="legend" :fields="fields"></if-user-form>
    
    <!-- Mismatched passwords warning -->
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <p class="password-err" v-show="passwordMismatch">Passwords do not match</p>
      </div>
    </div>
    
    <div class="form-group">
      <!-- Register button -->
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="register" type="button" class="btn">Register</button>
      </div>

      <br><br>

      <!-- Login link -->
      <div class="col-md-4 col-md-offset-4">
        <p>Already registered?
          <router-link to="/login">Login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import IfUserForm from './IfUserForm.vue' // Form used for supplying registration info
  import IfMessage from './IfMessage.vue' // Message component for displaying errors

  // Helper for resetting register data
  function defaultRegisterData() {
    return {
      // Form title
      legend: 'Register',
      // Email field
      email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '', format: function(val) { return val.trim(); } },
      // Username field
      username: { label: 'Username', type: 'text', placeholder: '5+ characters', value: '', format: function(val) {return val.trim(); } },
      // Password field
      password: { label: 'Password', type: 'password', placeholder: '10+ characters', value: '', format: function(val) { return val.trim(); } },
      // Confirm password field
      confirmPassword: { label: 'Confirm Password', type: 'password', placeholder: 'password', value: '', format: function(val) { return val.trim(); } }
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-register',

    // Components used by this component
    components: {
      IfUserForm,
      IfMessage
    },

    // Initialise registration data with defaults
    data() {
      return defaultRegisterData();
    },

    beforeRouteEnter(to, from, next) {
      next(registerComponent => {
        registerComponent.resetRegisterData();
      });
    },

    // Computed properties
    computed: {
      // Whether or not their is a password mistmatch
      passwordMismatch() {
        return this.password.value
          && this.confirmPassword.value
          && this.password.value !== this.confirmPassword.value;
      },

      fields() {
        return [ this.email, this.username, this.password, this.confirmPassword ];
      }
    },

    // Register component methods
    methods: {
      // Resets register data
      resetRegisterData() {
        Object.assign(this.$data, defaultRegisterData());
      },

      // Send POST request to register API, supplying email and password
      register(e) {
        if (!this.passwordMismatch && this.validFields()) {
          let registerDetails = { email: this.email.value, password: this.password.value, username: this.username.value };
          axios.post('/api/register', registerDetails)
            .then(this.registerSuccess)
            .catch(this.registerFailure);
        }
      },

      validFields() {
        if (!this.email.value) {          
          this.$refs.message.display('Please enter an email address.');
          return false;
        }
        if (!this.password.value || this.password.value.length < 10) {
          this.$refs.message.display('Password must have at least 10 characters.');
          return false;
        }
        if (!this.confirmPassword.value) {
          this.$refs.message.display('Please confirm your password.');
          return false;
        }
        return true;
      },

      // On success, navigate to login
      registerSuccess(res) {
        console.log("Register success");
        this.$router.push('/login');
      },

      // On failure, log the failure
      registerFailure(error) {
        console.log("Register failure");
        this.$refs.message.display(error.response.data);
      }
    }
  }
</script>

<style scoped>
  .password-err {
    color: red;
  }
</style>