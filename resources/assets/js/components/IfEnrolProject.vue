<template>
	<!-- Enrol project container -->
	<div id="enrolProject" class="container">

		<!-- Message component -->
    <if-message ref="message"></if-message>
 
		<!-- Form extension (class tags, class search) -->
 

    <div class="form-horizontal">
			<div class="form-group">

 
      <!--Legend -->
      <legend>{{legend}}</legend> 

				<div class="col-md-4 col-md-offset-4">
					<!-- ProjTags -->
					<label>Project</label>
					<div v-for="tag in projtags">
						<if-tag :label="tag.code" :onRemove="onRemove"></if-tag>
					</div>					
				</div>

				
				<div class="col-md-4 col-md-offset-4">
					<!-- Dropdown component -->
					<if-dropdown :url="projectSearchUrl" :getOptions="getProjOptions" :getName="getProjName" :onSelect="onProjSelect"></if-dropdown>
				</div>							



				<div class="col-md-4 col-md-offset-4">
					<!-- Tags -->
					<label>Class</label>
					<div v-for="tag in tags">
						<if-tag :label="tag.code" :onRemove="onRemove"></if-tag>
					</div>					
				</div>
				
				<div class="col-md-4 col-md-offset-4">
					<!-- Dropdown component -->
					<if-dropdown :url="classSearchUrl" :getOptions="getOptions" :getName="getName" :onSelect="onSelect"></if-dropdown>
				</div>							








    <!-- Field label -->

				<div class="col-md-4 col-md-offset-4">

					<label>Enrolment Key</label>
      <if-form-field-two-line v-for="(field, index) in fields" v-model="field.value" :label="field.label" :type="field.type" :placeholder="field.placeholder" :format="field.format" ></if-form-field>



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
import IfFormFieldTwoLine from './IfFormFieldTwoLine.vue' // Form field component used for this form's fields
import IfDropdown from './IfDropdown.vue' // Dropdown component for searching users
import IfTag from './IfTag.vue' // Tag component for adding users as class Classes
import IfMessage from './IfMessage.vue' // Message copmonent for displaying errors

// Helper for resetting class creation data
function defaultProjectEnrolmentData() {
	return {
		legend: 'Enrol project',  
		projectKey: { label: 'Enrolment key', type: 'password', placeholder: '10+ characters', value: '', format: function(val) { return val.trim(); } },
		classSearchUrl: '/api/classes/search?string=',
		tags: {}
	}
} 

export default {
	// Debug name and html tag of this component
	name: 'if-enrol-project',

	// Components used by this component
	components: {
		IfFormFieldTwoLine,
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
			return [  this.projectKey ];
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
			return option.code + ' ' + option.name;
		},

		// User selected an option in the dropdown
		onSelect(option) { 
			for (let tag in this.tags) {
				this.$delete(this.tags, tag);
			}
			this.$set(this.tags, option.code, option);
		},

		// User removed a tag
		onRemove(label) {
			this.$delete(this.tags, label);
		}
	}
}
</script>

<style scoped>
  legend {
    text-align: center;
  }
</style>