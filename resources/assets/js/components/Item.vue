<template>
  <li>
    <a class="item-name" v-on:click="toggle" href="#">{{active ? name : alt}}</a>
    <ul class="child-list" v-show="active && open">
      <li class="child" v-for="child in children"><a href="#" v-on:click="load(child, $event)">{{child}}</a></li>
    </ul>
  </li>
</template>

<script>
  export default {
    name: 'item',
    data() {
      return {
        open: true
      }
    },
    
    props: [
      'name',
      'alt',
      'active',
      'children'
    ],
    
    methods: {
      toggle(e) {
        e.preventDefault();
        if (this.active) {
          //this.open = !this.open;
        }
      },
      
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
