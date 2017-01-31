<template>
  <div v-show="ready" id="wrapper">
    <!-- Sidebar -->
    <div v-on:mousedown="toggleMenu('sidebar')" id="sidebar-wrapper">
      <ul class="sidebar-nav">
        <item :name="menu.projects.name" :alt="menu.projects.alt" :children="menu.projects.children" :active="menuOpen"></item>
        <item :name="menu.classes.name" :alt="menu.classes.alt" :children="menu.classes.children" :active="menuOpen"></item>
      </ul>
    </div>

    <div v-if="menuOpen" v-on:mousedown="toggleMenu" id="blanket"></div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <h1>Hello World</h1>
            <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Item from './Item.vue'
  import bus from '../bus.js'

  function defaultDashboard() {
    return {
      menuOpen: $('#wrapper').hasClass('toggled'),
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
      ready: false
    };
  }

  export default {
    name: 'dashboard',

    components: {
      Item
    },

    data() {
      return defaultDashboard();
    },

    created() {
      bus.$on('logout', this.resetDashboard);
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.loadDashboard();
      });
    },

    methods: {
      toggleMenu(caller) {
        if (caller !== 'sidebar' || !this.menuOpen) {
          this.menuOpen = !this.menuOpen;
          $('#wrapper').toggleClass('toggled');
        }
      },

      redirect(url, external) {
        if (external) {
          window.location.replace(url);
        }
        else {
          this.resetDashboard();
          this.$router.replace('/login');
        }
      },

      resetDashboard() {
        Object.assign(this.$data, defaultDashboard());
      },

      loadDashboard() {
        this.loadProjects();
        this.loadClasses();
        this.loadCommits(); // TEMP: testing VSTS redirect
        this.loadInnovations(); // TODO: display this in the dashboard
        this.ready = true;
      },

      loadProjects() {
        axios.get('/api/projects')
          .then(function (res) {
            this.projects.children = res.body['projects'] ? res.body['projects'] : [];
          })
          .catch(function (error) {
            console.log("Load projects failed");
          });
      },

      loadClasses() {
        axios.get('/api/classes')
          .then(function (res) {
            this.classes.children = res.body['classes'] ? res.body['classes'] : [];
          })
          .catch(function (error) {
            console.log("Load classes failed");
          });
      },

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

      loadInnovations() {
        axios.get('/api/innovation')
          .then((res) => console.log('Received innovations'))
          .catch((error) => console.log('Error loading innovations'));
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