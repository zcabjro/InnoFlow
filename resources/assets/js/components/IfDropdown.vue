<template>  
  <div class="btn-group open">
    
    <input v-model="search" class="form-control input-md">
    <ul id="optionList" v-show="!dirty && options && options.length > 0" class="dropdown-menu scrollable-menu open" role="menu">
        <li v-for="option in options"><a href="javascript:void(0)">{{option.username}}, {{option.email}}</a></li>        
    </ul>
  </div>
</template>

<script>
var _ = require('lodash');

export default {
  name: 'if-dropdown',

  data() {
    return {
      search: "",
      dirty: false,
      options: null
    }
  },

  watch: {
    search: function() {
      if (this.search.length > 1) {
        this.dirty = true;
        this.searchUsers();
      }      
    }
  },

  methods: {
    searchUsers: _.debounce(function() {
      console.log('sent');
      axios.get("/api/users/search?string=" + this.search)
        .then(this.onSearchSuccess)
        .catch(this.onSearchFailure);
    }, 500),

    onSearchSuccess(res) {
      console.log(res.data);
      this.options = res.data;
      this.dirty = false;
    },

    onSearchFailure(error) {
      console.log(error);
      this.options = null;
      this.dirty = false;
    }
  }
}
</script>

<style>
  .scrollable-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
  }
</style>