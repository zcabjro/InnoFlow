<template>
  <!-- Register container -->
  <div id="register" class="container">

    <!-- UserForm component -->
    <if-user-form :legend="legend" :fields="[email, password, confirmPassword]"></if-user-form>
    
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

  // Helper for resetting register data
  function defaultRegisterData() {
    return {
      // Form title
      legend: 'Register',
      // Email field
      email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '' },
      // Password field
      password: { label: 'Password', type: 'password', placeholder: 'password', value: '' },
      // Confirm password field
      confirmPassword: { label: 'Confirm Password', type: 'password', placeholder: 'password', value: '' }
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-register',

    // Components used by this component
    components: {
      IfUserForm
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
      passwordMismatch: function () {
        return this.password.value
          && this.confirmPassword.value
          && this.password.value !== this.confirmPassword.value;
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
        if (!this.passwordMismatch) {
          axios.post('/api/register', { email: this.email.value, password: this.password.value })
            .then(this.registerSuccess)
            .catch(this.registerFailure);
        }
      },

      // On success, navigate to login
      registerSuccess(res) {
        console.log("Success");
        this.$router.push('/login');
      },

      // On failure, log the failure
      registerFailure(error) {
        console.log("Failure");
      }
    }
  }
</script>

<style scoped>
  .password-err {
    color: red;
  }
</style>