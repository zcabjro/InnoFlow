<template>>
  <div id="login" class="container">
    <user-form :legend="legend" :fields="[email, password]"></user-form>
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="login" type="button" class="btn">Login</button>
      </div>
      <br><br>
      <div class="col-md-4 col-md-offset-4">
        <p>Need an account?
          <router-link to="/register">Register</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
  import UserForm from './UserForm.vue'
  import bus from '../bus.js'

  export default {
    name: 'login',

    components: {
      UserForm
    },

    data() {
      return {
        legend: 'Login',
        email: { label: 'Email', type: 'text', placeholder: 'mail@example.com', value: '' },
        password: { label: 'Password', type: 'password', placeholder: 'password', value: '' }
      }
    },

    methods: {
      login(e) {
          axios.post('/api/login', { email: this.email.value, password: this.password.value })
          .then(this.loginSuccess)
          .catch(this.loginFailure);
      },
      loginSuccess(res) {
        console.log("Login success");
        bus.$emit('login');
        this.$router.push('/dashboard');
      },
      loginFailure(error) {
        console.log("Failure");
      }
    }
  }
</script>

<style>

</style>