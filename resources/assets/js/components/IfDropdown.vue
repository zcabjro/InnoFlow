<template>    
  <ul id="optionList" v-show="options && options.length > 0" class="dropdown-menu scrollable-menu open" role="menu">
      <li v-for="option in options"><a v-on:click="select(option)" href="javascript:void(0)">{{getOptionName(option)}}</a></li>
  </ul>
</template>

<script>
export default {
  name: 'if-dropdown',

  data() {
    return {
    }
  },

  props: [
    'options',
    'getOptions',
    'getName',
    'onSelect'
  ],

  methods: {
    getOptionName(option) {
      return this.getName
        ? this.getName(option)
        : option.name;
    },

    select(option) {
      if (this.onSelect) {
        this.onSelect(option);
      }
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