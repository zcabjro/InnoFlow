<template>
	<!-- Create class container -->
	<div id="createClass" class="container">

		<!-- UserForm component -->
    <if-user-form :legend="legend" :fields="[className, classCode, classDescription, classKey]"></if-user-form>				

		<!-- Form extension (admin tags, admin search) -->
    <div class="form-horizontal">
			<div class="form-group">
				<div class="col-md-4 col-md-offset-4">
					<!-- Tags -->
					<label>Admins</label>
					<div v-for="tag in tags">
						<if-tag :label="tag.username" :onRemove="onRemove"></if-tag>
					</div>					
				</div>
				
				<div class="col-md-4 col-md-offset-4">
					<!-- Dropdown component -->
					<if-dropdown :url="userSearchUrl" :getOptions="getOptions" :getName="getName" :onSelect="onSelect"></if-dropdown>
				</div>							

				<!-- Spacer -->
				<div class="col-md-4 col-md-offset-4">
					<br>
				</div>

				<!-- Create button -->
				<div class="col-md-4 col-md-offset-4">
					<button v-on:click="create" type="button" class="btn">Create</button>
				</div>

			</div>			
    </div>
  </div>
</template>

<script>
import IfUserForm from './IfUserForm.vue' // Form used for supplying class creation fields
import IfDropdown from './IfDropdown.vue' // Dropdown component for searching users
import IfTag from './IfTag.vue' // Tag component for adding users as class admins

// Helper for resetting class creation data
function defaultClassCreationData() {
	return {
		legend: 'Create class',
		className: { label: 'Class name', type: 'text', placeholder: 'Software Abstractions and Systems Integration', value: '' },
		classCode: { label: 'Code', type: 'text', placeholder: 'COMPGS02', value: '', format: function(val) { return val.trim(); } },
		classDescription: { label: 'Description', type: 'textarea', placeholder: '', value: ''},
		classKey: { label: 'Enrolment key', type: 'password', placeholder: '', value: '', format: function(val) { return val.trim(); } },
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
			return this.className.value && this.classCode.value && this.classKey.value;
		},

		// Get users that are selected as admins
		admins() {
			let admins = [];
			for (let tag in this.tags) {
				if (this.tags.hasOwnProperty(tag)) {
					admins.push(this.tags[tag].userId);
				}
			}
			return admins.join();
		}
	},

	// Class creation component methods
	methods: {
		// Resets class creation data
		resetClassCreationData() {
			Object.assign(this.$data, defaultClassCreationData());
		},

		// Send a POST request to the class creation API, supplying field inputs
		create() {
			if (this.validFields) {				
				let classData = {
					name: this.className.value,
					code: this.classCode.value,
					description: this.classDescription.value,
					key: this.classKey.value,
					admins: this.admins
				};

				console.log(classData);

				axios.post('/api/classes', classData)
					.then(this.createSuccess)
					.catch(this.createFailure);
				}			
		},

		// On success, navigate to the dashboard
		createSuccess(res) {
		    console.log(res.response);
			console.log("Create class success");
			this.$router.push('/dashboard');
		},

		// On failure, log the failure
		createFailure(error) {
			console.log(error.response);
			console.log('Create class failure');
		},

		// How the dropdown menu should retrieve options from the server response data
		getOptions(data) {
			return data;
		},

		// How the dropdown menu should display each option
		getName(option) {
			return option.username + ', ' + option.email;
		},

		// User selected an option in the dropdown
		onSelect(option) {
			this.$set(this.tags, option.username, option);
		},

		// User removed a tag
		onRemove(label) {
			this.$delete(this.tags, label);
		}
	}
}
</script>

<style></style>