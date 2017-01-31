<template>
	<div id="createClass" class="container">
    <user-form :legend="legend" :fields="[className, classCode]"></user-form>
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="create" type="button" class="btn">Login</button>
      </div>
      <br><br>
      <div class="col-md-4 col-md-offset-4">
        <p>Need an account? <router-link to="/register">Register</router-link></p>
      </div>
    </div>
  </div>
</template>

<script>
import UserForm from './UserForm.vue'
export default {
	name: 'create-class',

	components: {
		UserForm
	},
	
	data() {
		return {
			legend: 'Create class',
			className: { label: 'Class name', type: 'text', placeholder: 'COMPGS02', value: '' },
			classCode: { label: 'Code', type: 'password', placeholder: 'Open, Sesame', value: '' }
		}
	},

	computed: {
		validFields() {
			return this.className.value && this.classCode.value;
		}	
	},

	methods: {
		create(e) {
    	axios.post('/api/createclass', { name: this.className.value, code: this.code.value })
				.then(this.createSuccess)
				.catch(this.createFailure);
		},
		createSuccess(res) {
			console.log('Success');
		},
		createFailure(error) {
			console.log('Failure');
		}
	}
}
</script>

<style>

</style>