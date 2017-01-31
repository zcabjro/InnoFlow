<template>
  <div id="register" class="container">
    <user-form :legend="legend" :fields="[email, password, confirmPassword]"></user-form>
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <p class="password-err" v-show="passwordMismatch">Passwords do not match</p>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="register" type="button" class="btn">Register</button>
      </div>
      <br><br>
      <div class="col-md-4 col-md-offset-4">
        <p>Already registered?
          <router-link to="/login">Login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import UserForm from './UserForm.vue'

  export default {
    name: 'register',
    components: {
      UserForm
    },
    data() {
      return {

        legend: 'Register',
        email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '' },
        password: { label: 'Password', type: 'password', placeholder: 'password', value: '' },
        confirmPassword: { label: 'Confirm Password', type: 'password', placeholder: 'password', value: '' }
      }
    },
    computed: {
      passwordMismatch: function () {
        return this.password.value
          && this.confirmPassword.value
          && this.password.value !== this.confirmPassword.value;
      }
    },
    methods: {
      register(e) {
        if (!this.passwordMismatch) {
          axios.post('/api/register', { email: this.email.value, password: this.password.value })
            .then(this.registerSuccess)
            .catch(this.registerFailure);
        }
      },
      registerSuccess(res) {
        console.log("Success");
        this.$router.push('/login');
      },
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