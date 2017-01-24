<template>
  <div class="container">
    <form id="register" class="form-horizontal">
      <fieldset>
        
        <!-- Register legend -->
        <legend>Register</legend>
        
        <!-- Email -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="email">Email</label>  
          <div class="col-md-4">
            <input id="email" name="email" type="text" placeholder="inno.flow@email.com" class="form-control input-md" required>
          </div>
        </div>
        
        <!-- Password -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="password">Password</label>
          <div class="col-md-4">
            <input v-model="password" id="password" name="password" type="password" placeholder="password" class="form-control input-md" required>
          </div>
        </div>
        
        <!-- Confirm assword -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="password">Confirm Password</label>
          <div class="col-md-4">
            <input v-model="confirmPassword" id="confirm-password" name="password" type="password" placeholder="password" class="form-control input-md" required>
            <p class="password-err" v-if="password && confirmPassword && (password !== confirmPassword)">Passwords do not match</p>
          </div>
        </div>
  
        <div class="form-group">
          <div class="col-md-4 col-md-offset-4">
            <button type="button" class="btn" v-on>Register</button>
          </div>
          <br><br>
          <div class="col-md-4 col-md-offset-4">
            <p>Already registered? <router-link to="/login">Login</router-link></p>
          </div>
        </div>
        
      </fieldset>
    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        email: '',
        password: '',
        confirmPassword: ''
      }
    },
    
    methods: {
      register(e) {
        
      }
    }
    
  }
</script>

<style>
  #register {
    padding-top: 50px;
  }

  #register legend {
    text-align: center;
  }
  
  .password-err {
    color: red;
  }
</style>
