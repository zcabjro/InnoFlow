<template>
	<!-- Create class container -->
	<div id="createClass" class="container">

		<!-- UserForm component -->
    <if-user-form :legend="legend" :fields="[className, classCode]"></if-user-form>

    <div class="form-group">
			<!-- Create button -->
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="create" type="button" class="btn">Create</button>
      </div>      
    </div>
  </div>
</template>

<script>
import IfUserForm from './IfUserForm.vue' // Form used for supplying class creation fields

// Helper for resetting class creation data
function defaultClassCreationData() {
	return {
		legend: 'Create class',
		className: { label: 'Class name', type: 'text', placeholder: 'COMPGS02', value: '' },
		classCode: { label: 'Code', type: 'password', placeholder: 'Open, Sesame', value: '' }
	}
}

export default {
	// Debug name and html tag of this component
	name: 'if-create-class',

	// Components used by this component
	components: {
		IfUserForm
	},
	
	// Initialise class creation data with defaults
	data() {
		return defaultClassCreationData();
	},

	// Computed properties
	computed: {
		// Whether input fields are valid or not
		validFields() {
			return this.className.value && this.classCode.value;
		}	
	},

	// Class creation component methods
	methods: {
		// Send a POST request to the class creation API, supplying field inputs
		create(e) {
    	axios.post('/api/createclass', { name: this.className.value, code: this.code.value })
				.then(this.createSuccess)
				.catch(this.createFailure);
		},

		// On success, navigate to the dashboard
		createSuccess(res) {
			console.log('Create class success');
			this.$router.push('/dashboard');
		},

		// On failure, log the failure
		createFailure(error) {
			console.log('Create class failure');
		}
	}
}
</script>

<style></style>