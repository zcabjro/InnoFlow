<template>
  <!-- List item -->
  <li>
    <!-- Item name -->
    <a class="item-name">{{active ? options.name : options.alt}}</a>
    <!-- Item children -->
    <ul class="child-list" v-show="active">
      <li class="child" v-for="child in options.children"><a href="#" v-on:click="load(child, $event)">{{getChildName(child)}}</a></li>
      <li class="child"><a href="#" v-on:click="add($event)">{{options.addText}}</router-link></li>      
      <slot></slot>
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
        
      }
    },

    // Properties supplied by parent component
    props: [
      // { name, alt, children, addText, addUrl, getName }
      'options',
      // Whether or not item is active
      'active'
    ],
    
    // Item component methods
    methods: {      
      // TODO: Load the selected child
      load(item, e) {
        e.preventDefault();
        if (this.options.selectUrl) {
          let id = item.id ? item.id : '';
          let url = this.options.selectUrl.endsWith('/') ? this.options.selectUrl : this.options.selectUrl + '/';
          this.$router.push(url + id);
        }        
      },

      add(e) {
        e.preventDefault();
        this.$router.push(this.options.addUrl);
      },

      getChildName(child) {
        return this.options && this.options.getName
          ? this.options.getName(child)
          : child;
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
