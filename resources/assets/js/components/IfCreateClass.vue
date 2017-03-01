<template>
	<!-- Create class container -->
	<div id="createClass" class="container">

		<!-- Message component -->
    <if-message ref="message"></if-message>

		<!-- UserForm component -->
    <if-user-form :legend="legend" :fields="fields"></if-user-form>				

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
					<if-search-dropdown :onSearch="onSearch" :getOptions="getOptions" :getName="getName" :onSelect="onSelect"></if-search-dropdown>
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
import IfSearchDropdown from './IfSearchDropdown.vue' // Dropdown component for searching users
import IfTag from './IfTag.vue' // Tag component for adding users as class admins
import IfMessage from './IfMessage.vue' // Message copmonent for displaying errors

// Helper for resetting class creation data
function defaultClassCreationData() {
	return {
		legend: 'Create class',
		className: { label: 'Class name', type: 'text', placeholder: '5+ characters', value: '' },
		classCode: { label: 'Code', type: 'text', placeholder: '5+ characters', value: '', format: function(val) { return val.trim(); } },
		classDescription: { label: 'Description', type: 'textarea', placeholder: '20+ characters', value: ''},
		classKey: { label: 'Enrolment key', type: 'password', placeholder: '10+ characters', value: '', format: function(val) { return val.trim(); } },
		userSearchUrl: '/api/classes/admins/search?string=',
		tags: {}
	}
}

export default {
	// Debug name and html tag of this component
	name: 'if-create-class',

	// Components used by this component
	components: {
		IfUserForm,
		IfSearchDropdown,
		IfTag,
		IfMessage
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
		fields() {			
			return [ this.className, this.classCode, this.classDescription, this.classKey ];
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
			if (this.validFields()) {				
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

		// Checks if fields are valid, displaying message if not
		validFields() {
			if (!this.className.value || this.className.value.length < 5) {
				this.$refs.message.display('Name must be at least 5 characters.');
				return false;
			}
			if (!this.classCode.value || this.classCode.value.length < 5) {
				this.$refs.message.display('Code must be at least 5 characters.');
				return false;
			}
			if (!this.classDescription.value || this.classDescription.value.length < 20) {
				this.$refs.message.display('Description must be at least 20 characters.');
				return false;
			}
			if (!this.classKey.value || this.classKey.value.length < 10) {
				this.$refs.message.display('Key must be at least 10 characters.');
				return false;
			}
			return true;
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
			this.$refs.message.display(error.response.data ? error.response.data : 'Failed');		
			console.log('Create class failure');
		},

		onSearch(searchInput, resultsCallback) {
			axios.get(this.userSearchUrl + searchInput)
        .then((res) => {
					let admins = this.getOptions(res.data);
					resultsCallback(admins);
				})
        .catch((error) => {
					console.log(error);
				});
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