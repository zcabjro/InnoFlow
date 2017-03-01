<template>
	<!-- Enrol project container -->
	<div id="enrolProject" class="container">

		<!-- Message component -->
    <if-message ref="message"></if-message>

		<!-- UserForm component -->
    <if-user-form :legend="legend" :fields="fields"></if-user-form>				

		<!-- Form extension (admin tags, admin search) -->
    <div class="form-horizontal">
			<div class="form-group">
				<div class="col-md-4 col-md-offset-4">
					<!-- Tags -->
					<label>Classes</label>
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

				<!-- Enrol button -->
				<div class="col-md-4 col-md-offset-4">
					<button v-on:click="enrol" type="button" class="btn">Enrol</button>
				</div>

			</div>			
    </div>
  </div>
</template>

<script>
import IfUserForm from './IfUserForm.vue' // Form used for supplying class creation fields
import IfDropdown from './IfDropdown.vue' // Dropdown component for searching users
import IfTag from './IfTag.vue' // Tag component for adding users as class Classes
import IfMessage from './IfMessage.vue' // Message copmonent for displaying errors

// Helper for resetting class creation data
function defaultProjectEnrolmentData() {
	return {
		legend: 'Enrol project',
		projectName: { label: 'Class name', type: 'text', placeholder: '5+ characters', value: '' },
		projectCode: { label: 'Code', type: 'text', placeholder: '5+ characters', value: '', format: function(val) { return val.trim(); } },
		projectDescription: { label: 'Description', type: 'textarea', placeholder: '20+ characters', value: ''},
		projectKey: { label: 'Enrolment key', type: 'password', placeholder: '10+ characters', value: '', format: function(val) { return val.trim(); } },
		userSearchUrl: '/api/classes/admins/search?string=',
		tags: {}
	}
}

export default {
	// Debug name and html tag of this component
	name: 'if-enrol-project',

	// Components used by this component
	components: {
		IfUserForm,
		IfDropdown,
		IfTag,
		IfMessage
	},
	
	// Initialise class creation data with defaults
	data() {
		return defaultProjectEnrolmentData();
	},

	// Reset the class creation data each time we navigate to this route
	beforeRouteEnter(to, from, next) {		
		next(enrolProjectComponent => {			
			enrolProjectComponent.resetProjectEnrolmentData();
		});
	},

	// Computed properties
	computed: {
		fields() {			
			return [ this.projectName, this.projectCode, this.projectDescription, this.projectKey ];
		},

		// Get users that are selected as Projects
		Projects() {
			let Projects = [];
			for (let tag in this.tags) {
				if (this.tags.hasOwnProperty(tag)) {
					Projects.push(this.tags[tag].userId);
				}
			}
			return Projects.join();
		}
	},

	// Class creation component methods
	methods: {
		// Resets class creation data
		resetProjectEnrolmentData() {
			Object.assign(this.$data, defaultProjectEnrolmentData());
		},

		// Send a POST request to the class creation API, supplying field inputs
		enrol() {
			if (this.validFields()) {				
				let projectData = {
					name: this.projectName.value,
					code: this.projectCode.value,
					description: this.projectDescription.value,
					key: this.projectKey.value,
					Projects: this.Projects
				};

				console.log(projectData);

				axios.post('/api/classes', projectData)
					.then(this.enrolSuccess)
					.catch(this.enrolFailure);
				}			
		},

		// Checks if fields are valid, displaying message if not
		validFields() {
			if (!this.projectName.value || this.projectName.value.length < 5) {
				this.$refs.message.display('Name must be at least 5 characters.');
				return false;
			}
			if (!this.projectCode.value || this.projectCode.value.length < 5) {
				this.$refs.message.display('Code must be at least 5 characters.');
				return false;
			}
			if (!this.projectDescription.value || this.projectDescription.value.length < 20) {
				this.$refs.message.display('Description must be at least 20 characters.');
				return false;
			}
			if (!this.projectKey.value || this.projectKey.value.length < 10) {
				this.$refs.message.display('Key must be at least 10 characters.');
				return false;
			}
			return true;
		},

		// On success, navigate to the dashboard
		enrolSuccess(res) {
		  console.log(res.response);
			console.log("Enrol project success");
			this.$router.push('/dashboard');
		},

		// On failure, log the failure
		enrolFailure(error) {
			console.log(error.response);
			this.$refs.message.display(error.response.data ? error.response.data : 'Failed');		
			console.log('Enrol project failure');
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