<template>
  <div>
    <!-- Message component -->
    <if-message ref="message"></if-message>

    <!-- Dashboard wrapper -->
    <div v-show="display" id="wrapper">

      <!-- Sidebar -->
      <div v-on:mousedown="toggleMenu('sidebar')" id="sidebar-wrapper">
        <ul class="sidebar-nav">
          <if-item :options="menu.innovations" :active="menuOpen"></if-item>
          <if-item :options="menu.projects" :active="menuOpen">
            <a href="#" v-on:click="refreshProjects($event)">[+] Refresh</a>  
          </if-item>          
          <if-item :options="menu.classes" :active="menuOpen"></if-item>
        </ul>
      </div>

      <!-- Nested route -->    
      <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <router-view></router-view>          
          </div>
        </div>
      </div>
      
      <!-- Empty blanket space for closing menu -->
      <div v-if="menuOpen" v-on:mousedown="toggleMenu" id="blanket"></div>

    </div>
  </div>
</template>

<script>
  import IfItem from './IfItem.vue' // Item component used for listing projects and classes
  import bus from '../bus.js' // Global event bus
  import IfMessage from './IfMessage.vue' // Message component for displaying messages to the user

  // Helper for resetting dashboard data
  function defaultDashboardData() {
    return {
      // Whether or not side-bar is open
      menuOpen: $('#wrapper').hasClass('toggled'),

      // Menu data
      menu: {
        innovations: {
          name: 'Home',
          alt: 'H',
          children: [{
            name: 'Innovations'
          }],
          selectUrl: '/dashboard/innovations',
          addText: '[?] About',
          addUrl: '/vscodeExtension',
          getName(p) {
            return p.name;
          }
        },

        projects: {
          name: 'Projects',
          alt: 'P',
          children: [],
          selectUrl: '/dashboard/projects',
          addText: '[+] Enrol',
          addUrl: '/enrol',
          getName(p) {
            return p.name;
          }
        },
        classes: {
          name: 'Classes',
          alt: 'C',
          children: [],
          selectUrl: '/dashboard/classes',
          addText: '[+] Add',
          addUrl: '/create',
          getName(c) {
            return c.code;
          }
        }
      },
      
      // Whether the dashboard is ready for display
      display: false
    };
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-dashboard',

    // Components used by this component
    components: {
      IfItem,
      IfMessage
    },

    // Initialise dashboard data with defaults
    data() {
      return defaultDashboardData();
    },

    // Called when the component is first created (note: not on navigation)
    created() {
      // Listen for the logout event for updating the dashboard contents
      bus.$on('logout', this.resetDashboardData);
    },

    // Request the dashboard contents each time we navigate to this route
    beforeRouteEnter(to, from, next) {
      next(dashboardComponent => {
        // Reset side panel
        dashboardComponent.menuOpen = false;
        $('#wrapper').toggleClass('toggled', false);

        // Load dashboard
        dashboardComponent.loadDashboard();        
      });
    },

    // Dashboard component methods
    methods: {
      // Resets the dashboard contents
      resetDashboardData() {        
        Object.assign(this.$data, defaultDashboardData());
      },

      // Toggles the sidebar menu
      toggleMenu(caller) {
        if (caller !== 'sidebar' || !this.menuOpen) {
          this.menuOpen = !this.menuOpen;
          $('#wrapper').toggleClass('toggled');
        }
      },

      // Helper for redirecting the user
      redirect(url, external) {
        if (external) {
          window.location.replace(url);
        }
        else {
          this.resetDashboardData();
          this.$router.replace(url);
        }
      },

      // Request dashboard contents
      loadDashboard() {
        this.checkVSTSAuth((isAuthorised, redirectUrl) => {          
          this.loadClasses();
          if (isAuthorised) {
            console.log("VSTS Authorised!");
            this.loadProjects();
            this.loadCommits(); // TEMP: testing VSTS redirect                    
          }
          else if (redirectUrl && confirm('Redirect for VSTS auth?')) {
            //this.$refs.message.display('Missing VSTS Authorisation.');
            //console.log("Not VSTS Authorised!");
            this.redirect(redirectUrl, true);
          }
          
          // TODO: Set this when all content has been received
          this.display = true;
        });
      },

      // Check VSTS auth state
      checkVSTSAuth(callback) {
        axios.get('/api/vsts')
          .then((res) => {
            callback(res.data.isAuthorized, res.data.url);
          })
          .catch((error) => {
            console.log(error);
            callback(false);
          });
      },

      refreshProjects(e) {
        e.preventDefault();
        this.loadProjects(true);
      },

      // Request projects
      loadProjects(refresh) {
        axios.get('/api/projects?refresh=' + (refresh ? 1 : 0))
          .then((res) => {
            // Update project list
            this.menu.projects.children = res.data ? res.data : [];
          })
          .catch(function (error) {
            // Log failure
            console.log(error.response);
            console.log("Load projects failed");
          });
      },

      // Request classes
      loadClasses() {
        axios.get('/api/classes')
          .then((res) => {
            // Update class list
            this.menu.classes.children = res.data ? res.data : [];
          })
          .catch(function (error) {
            // Log failure     
            console.log(error.response);
            console.log("Load classes failed");
          });
      }      
    }
  }
</script>

<style>  
  #blanket {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
  }  
</style>