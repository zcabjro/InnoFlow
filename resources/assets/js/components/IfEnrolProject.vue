<template>
	<!-- Enrol project container -->
	<div id="enrolProject" class="container">

		<!-- Message component -->
    <if-message ref="message"></if-message>
 
    <div class="form-horizontal">
			<div class="form-group">
 
      <!--Legend -->
      <legend>{{legend}}</legend> 

				<div class="col-md-4 col-md-offset-4">
					<label>Project</label>
				</div>
				<div class="col-md-4 col-md-offset-4">
					<!-- Dropdown component -->
					<if-search-dropdown :onSearch="onSearchProjects" :getOptions="getProjects" :getName="getProjectName" :onSelect="onSelectProject"></if-search-dropdown>
				</div>
				<div class="col-md-4 col-md-offset-4">
					<!-- Selected project -->
					<p>{{projectName}}</p>
				</div>

				<div class="col-md-4 col-md-offset-4">
					<label>Class</label>
				</div>
				<div class="col-md-4 col-md-offset-4">
					<!-- Search Dropdown component -->
					<if-search-dropdown :onSearch="onSearchClasses" :getOptions="getClasses" :getName="getClassName" :onSelect="onSelectClass"></if-dropdown>
				</div>
				<div class="col-md-4 col-md-offset-4">
					<!-- Selected class -->
					<p>{{className}}</p>
				</div>	

				<!-- Enrolment key -->			
				<if-form-field v-model="enrolmentKey" label="Enrolment Key" type="password" placeholder="Key" :format="trimKey"></if-form-field>

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
import IfFormField from './IfFormField.vue' // Form field for enrolment key
import IfSearchDropdown from './IfSearchDropdown.vue' // Dropdown component used for searching projects/classes
import IfMessage from './IfMessage.vue' // Message copmonent for displaying errors

// Helper for resetting class creation data
function defaultProjectEnrolmentData() {
	return {
		legend: 'Enrol project', 
		selectedProject : null,
		selectedClass: null,
		enrolmentKey: '',
		projects: [],
		classSearchUrl: '/api/classes/search?string='
	}
} 

export default {
	// Debug name and html tag of this component
	name: 'if-enrol-project',

	// Components used by this component
	components: {
		IfFormField,
		IfSearchDropdown,
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
			enrolProjectComponent.loadProjects();
		});
	},

	// Computed properties
	computed: {
		projectName() {
			return this.selectedProject ? this.getProjectName(this.selectedProject) : '';
		},

		className() {
			return this.selectedClass ? this.getClassName(this.selectedClass) : '';
		}
	},

	// Class creation component methods
	methods: {
		// Resets class creation data
		resetProjectEnrolmentData() {
			Object.assign(this.$data, defaultProjectEnrolmentData());
		},

		loadProjects() {
			axios.get('/api/projects')
				.then((res) => {
					this.projects = res.data;
				})
				.catch((error) => {
					console.log(error);
				})
		},

		trimKey(val) {
			return val.trim();
		},

		// Send a POST request to the class creation API, supplying field inputs
		enrol() {			
			if (this.validFields()) {				
				let enrolmentData = { 
					code: this.selectedClass.code,
					key: this.enrolmentKey
				};

				let postRoute = '/api/projects/' + this.selectedProject.id + '/enrol';

				axios.post(postRoute, enrolmentData)
					.then(this.enrolSuccess)
					.catch(this.enrolFailure);
				}			
		},

		// Checks if fields are valid, displaying message if not
		validFields() { 
			if (!this.enrolmentKey) {
				this.$refs.message.display('Please enter an enrolment key.');
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

		onSearchProjects(searchInput, resultsCallback) {
			let results = [];
			for (let i = 0; i < this.projects.length; i++) {
				if (this.projects[i].name.indexOf(searchInput) >= 0) {
					results.push(this.projects[i]);
				}
			}
			resultsCallback(results);
		},

		onSearchClasses(searchInput, resultsCallback) {
			axios.get(this.classSearchUrl + searchInput)
        .then((res) => {
					let classes = this.getClasses(res.data);
					resultsCallback(classes);
				})
        .catch((error) => {
					console.log(error);
				});
		},
		
		getProjects(data) {
			return data;
		},

		getClasses(data) {
			return data;
		},

		getProjectName(projectOption) {
			return projectOption.name;
		},

		getClassName(classOption) {
			return classOption.code;
		},

		onSelectProject(projectOption) { 
			this.selectedProject = projectOption;
		},

		onSelectClass(classOption) {
			this.selectedClass = classOption;
		}
	}
}
</script>

<style scoped>
  legend {
    text-align: center;
  }
</style>