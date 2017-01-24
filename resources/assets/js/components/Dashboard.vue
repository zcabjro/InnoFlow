<template>
  <div id="wrapper">
    <!-- Sidebar -->
    <div v-on:mousedown="toggleMenu('sidebar')" id="sidebar-wrapper">
      <ul class="sidebar-nav">
        <item v-for="item in menu" :name="item.name" :alt="item.alt" :active="menuOpen" :children="item.children"></item>
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
    data() {
      return {
        menuOpen: $('#wrapper').hasClass('toggled'),
        menu: [{
          name: 'Projects',
          alt: 'P',
          children: ['A', 'B']
        }, {
          name: 'Classes',
          alt: 'C',
          children: []
        }]
      }
    },
    
    components : {
      Item
    },
    
    methods: {
      toggleMenu(caller) {
        if (caller !== 'sidebar' || !this.menuOpen) {
          this.menuOpen = !this.menuOpen;
          $("#wrapper").toggleClass("toggled"); 
        }
      },
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