<template>
	<!-- Create class container -->
	<div id="createClass" class="container">

		<!-- UserForm component -->
    <if-user-form :legend="legend" :fields="[className, classCode, classDescription]"></if-user-form>				

    <div class="form-group">
			<div class="col-md-4 col-md-offset-4">
				<!-- Tags -->
				<if-tag v-for="tag in tags" :label="tag.username" :onRemove="onRemove"></if-tag>
			</div>
			
			<div class="col-md-4 col-md-offset-4">
				<!-- Dropdown component -->
				<if-dropdown :url="userSearchUrl" :getOptions="getOptions" :getName="getName" :onSelect="onSelect"></if-dropdown>
			</div>

			<!-- Create button -->
      <div class="col-md-4 col-md-offset-4">
        <button v-on:click="create" type="button" class="btn">Create</button>
      </div>			
			
    </div>
  </div>
</template>

<script>
import IfUserForm from './IfUserForm.vue' // Form used for supplying class creation fields
import IfDropdown from './IfDropdown.vue' // Dropdown component
import IfTag from './IfTag.vue' // Tag component

// Helper for resetting class creation data
function defaultClassCreationData() {
	return {
		legend: 'Create class',
		className: { label: 'Class name', type: 'text', placeholder: 'COMPGS02', value: '' },
		classCode: { label: 'Code', type: 'password', placeholder: 'Open, Sesame', value: '' },
		classDescription: { label: 'Description', type: 'textarea', placeholder: '', value: ''},
		userSearchUrl: '/api/users/search?string=',
		tags: {}
	}
}

export default {
	// Debug name and html tag of this component
	name: 'if-create-class',

	// Components used by this component
	components: {
		IfUserForm,
		IfDropdown,
		IfTag
	},
	
	// Initialise class creation data with defaults
	data() {
		return defaultClassCreationData();
	},

	// Reset the class creation data each time we navigate to this route
	beforeRouteEnter(to, from, next) {
		next(createClassComponent => {
			createClassComponent.resetClassCreationData();
		});
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
		// Resets class creation data
		resetClassCreationData() {
			Object.assign(this.$data, defaultClassCreationData());
		},

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
		},

		getOptions(data) {
			return data;
		},

		getName(option) {
			return option.username + ', ' + option.email;
		},

		onSelect(option) {
			this.$set(this.tags, option.username, option);
		},

		onRemove(label) {
			this.$delete(this.tags, label);
		}
	}
}
</script>

<style></style>