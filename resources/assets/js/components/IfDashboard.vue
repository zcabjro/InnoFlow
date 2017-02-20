<template>
  <!-- Dashboard wrapper -->
  <div v-show="ready" id="wrapper">

    <!-- Sidebar -->
    <div v-on:mousedown="toggleMenu('sidebar')" id="sidebar-wrapper">
      <ul class="sidebar-nav">
        <if-item :name="menu.projects.name" :alt="menu.projects.alt" :children="menu.projects.children" :active="menuOpen" addUrl="/create"></if-item>
        <if-item :name="menu.classes.name" :alt="menu.classes.alt" :children="menu.classes.children" :active="menuOpen" addUrl="/create"></if-item>
      </ul>
    </div>

    <!-- Nested route -->    
    <div id="page-content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div v-show="highlighted" class="col-lg-12">
            <h1>Innovations</h1>              
              <div v-for="i in innovations">
                <p>{{i.created}}</p>
                <vue-markdown :source="i.code"></vue-markdown>
              </div>
            <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Empty blanket space for closing menu -->
    <div v-if="menuOpen" v-on:mousedown="toggleMenu" id="blanket"></div>

  </div>
</template>

<script>
  import IfItem from './IfItem.vue' // Item component used for listing projects and classes
  import bus from '../bus.js' // Global event bus
  import VueMarkdown from 'vue-markdown'

  // Helper for resetting dashboard data
  function defaultDashboardData() {
    return {
      // Whether the menu is toggled on or not
      menuOpen: $('#wrapper').hasClass('toggled'),
      // Menu data
      menu: {
        projects: {
          name: 'Projects',
          alt: 'P',
          children: []
        },
        classes: {
          name: 'Classes',
          alt: 'C',
          children: []
        }
      },
      innovations: [],
      // Whether the dashboard is ready for display
      ready: false,
      highlighted: false
    };
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-dashboard',

    // Components used by this component
    components: {
      IfItem,
      VueMarkdown
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
          this.$router.replace('/login');
        }
      },

      // Request dashboard contents
      loadDashboard() {
        this.loadProjects();
        this.loadClasses();
        this.loadCommits(); // TEMP: testing VSTS redirect
        this.loadInnovations();
        
        // TODO: Set this when all contents has been received
        this.ready = true;
      },

      // Request projects
      loadProjects() {
        axios.get('/api/projects')
          .then((res) => {
            // Update project list
            this.menu.projects.children = res.data['projects'] ? res.data['projects'] : [];
          })
          .catch(function (error) {
            // Log failure
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
            console.log(error);
            console.log("Load classes failed");
          });
      },

      // Request commits (temporary for testing VSTS redirect)
      loadCommits() {
        axios.post('/api/commits')
          .then(function (res) {
            console.log(res.data);
          })
          .catch((error) => {
            console.log(error.response);
            if (error.response && error.response.data) {
              let data = error.response.data;
              if (typeof data.error === 'string') {
                // TODO: establish a proper error format
                console.log('login again');
                this.redirect('/login', false);
              }
              else if (data.error && data.error.url && confirm('Redirect for VSTS auth?')) {
                this.redirect(data.error.url, true);
              }
            }
            else {
              console.log('ERROR: ' + error.message);
            }
          });
      },

      // Request innovations
      loadInnovations() {
        axios.get('/api/innovations')
          .then((res) => {
            this.highlighted = false;
            this.innovations = res.data;  
            setTimeout(() => {
              window.Prism.highlightAll();
              this.highlighted = true;
            }, 300);         
          })
          .catch(function(error) { console.log('Error loading innovations'); });
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