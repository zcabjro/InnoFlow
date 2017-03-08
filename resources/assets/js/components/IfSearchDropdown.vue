<template>  
  <div class="btn-group open">    
    <input v-model="searchInput" v-on:blur="resetOptions" class="form-control input-md">
    <if-dropdown :options="options" :getName="getName" :onSelect="onSelect" v-if="!dirty"></if-dropdown>
  </div>
</template>

<script>
  import IfDropdown from './IfDropdown.vue'
  import HttpErrorHelper from '../httpErrorHelper.js'

  var _ = require('lodash');

  export default {
    name: 'if-search-dropdown',

    components: {
      IfDropdown
    },

    data() {
      return {
        searchInput: '',
        dirty: false,
        options: null
      }
    },

    props: [
      'onSearch',
      'getName',
      'onSelect',
      ''
    ],

    watch: {
      searchInput: function() {
        this.search();
      }
    },

    methods: {
      search: _.debounce(function() {
        if (this.onSearch && this.searchInput.length > 1) {
          this.dirty = true;
          this.onSearch(this.searchInput, this.resultsCallback);
        }
        else {
          this.options = null;
          this.dirty = false;
        }
      }, 300),    

      resultsCallback(results) {
        this.options = results;
        this.dirty = false;
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
          this.searchInput = '';
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