<template>>
  <div class="container">
    <form id="login" class="form-horizontal">
      <fieldset>
        
        <!-- Login legend -->
        <legend>Login</legend>
        
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
            <input id="password" name="password" type="password" placeholder="password" class="form-control input-md" required>
          </div>
        </div>
  
        <div class="form-group">
          <div class="col-md-4 col-md-offset-4">
            <button type="button" class="btn">Login</button>
          </div>
          <br><br>
          <div class="col-md-4 col-md-offset-4">
            <p>Need an account? <router-link to="/register">Register</router-link></p>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</template>

<script>
  
</script>

<style scoped>

  #login {
    padding-top: 50px;
  }

  #login legend {
    text-align: center;
  }
</style>
