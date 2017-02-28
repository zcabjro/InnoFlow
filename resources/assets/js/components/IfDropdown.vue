<template>  
  <div class="btn-group open">
    
    <input v-model="search" v-on:blur="resetOptions" class="form-control input-md">
    <ul id="optionList" v-show="search && !dirty && options && options.length > 0" class="dropdown-menu scrollable-menu open" role="menu">
        <li v-for="option in options"><a v-on:click="select(option)" href="javascript:void(0)">{{getOptionName(option)}}</a></li>
    </ul>
  </div>
</template>

<script>
var _ = require('lodash');

export default {
  name: 'if-dropdown',

  data() {
    return {
      search: '',
      dirty: false,
      options: null
    }
  },

  props: [
    'url',
    'getOptions',
    'getName',
    'onSelect'
  ],

  watch: {
    search: function() {
      if (this.search.length > 1) {
        this.dirty = true;
        this.searchUsers();
      }
      else {
        this.options = null;
        this.dirty = false;
      }
    }
  },

  methods: {
    searchUsers: _.debounce(function() {
      axios.get(this.url + this.search)
        .then(this.onSearchSuccess)
        .catch(this.onSearchFailure);
    }, 300),

    onSearchSuccess(res) {
      this.options = this.getDataOptions(res.data);
      this.dirty = false;
    },

    onSearchFailure(error) {
      console.log(error);
      this.options = null;
      this.dirty = false;
    },

    getDataOptions(data) {
      return this.getOptions
        ? this.getOptions(data)
        : data;
    },

    getOptionName(option) {
      return this.getName
        ? this.getName(option)
        : option.name;
    },

    select(option) {
      if (this.onSelect) {
        this.onSelect(option);
      }
    },

    resetOptions() {
      setTimeout(() => {
        this.search = '';
        this.options = null;
      }, 200);
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