<template>
  <div id="wrapper">
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

  export default {
    name: 'dashboard',
    data() {
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
        }
      }
    },

    components: {
      Item
    },

    created: function () {
      this.loadProjects();
    },

    watch: {
      '$route': 'loadProjects'
    },

    methods: {
      toggleMenu(caller) {
        if (caller !== 'sidebar' || !this.menuOpen) {
          this.menuOpen = !this.menuOpen;
          $('#wrapper').toggleClass('toggled');
        }
      },
      loadProjects() {
        this.$http
          .get('/api/projects')
          .then(this.loadSuccess, this.loadFailure);
      },
      loadSuccess(res) {
        this.projects.children = res.body['projects'] ? res.body['projects'] : [];
        this.classes.children = res.body['classes'] ? res.body['classes'] : [];
      },
      loadFailure(res) {
        console.log('Failed to load projects');
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