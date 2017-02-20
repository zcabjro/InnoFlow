<template>
  <!-- List item -->
  <li>
    <!-- Item name -->
    <a class="item-name" v-on:click="toggle" href="#">{{active ? name : alt}}</a>
    <!-- Item children -->
    <ul class="child-list" v-show="active && open">
      <li class="child" v-for="child in children"><a href="#" v-on:click="load(child, $event)">{{child.code}}</a></li>
      <li class="child"><router-link to="addUrl">[+] Add</router-link></li>
    </ul>
  </li>
</template>

<script>
  export default {
    // Debug name and html tag of this component
    name: 'if-item',

    // Initialise item data with defaults
    data() {
      return {
        open: true
      }
    },
    
    // Properties supplied by parent component
    props: [
      // Item name
      'name',
      // Alternative name used when inactive
      'alt',
      // Whether or not item is active
      'active',
      // Item children
      'children',
      // Link to add page
      'addUrl'
    ],
    
    // Item component methods
    methods: {
      // Toggles children on/off
      toggle(e) {
        e.preventDefault();
        if (this.active) {
          //this.open = !this.open;
        }
      },
      
      // TODO: Load the selected child
      load(project, e) {
        e.preventDefault();
        alert("Loading " + project);
      }
    }
  }
  
</script>

<style scoped>  
  .item-name {
    height: 65px;
    font-size: 18px;
    line-height: 60px;
  }
  
  .child-list {
    list-style-type: none;
  }  
</style>
