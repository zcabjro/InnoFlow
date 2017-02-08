<template>
  <span>{{label}} <a v-on:click="remove" href="javascript:void(0)"><span class="badge">X</span></a></span>
</template>

<script>
  export default {
    name: 'if-tag',

    data() {
      return {        
      }
    },

    props: [
      'label',
      'onRemove'
    ],

    methods: {
      remove() {        
        if (this.onRemove) {
          this.onRemove(this.label);
        }
      }
    }
  }
</script>

<style></style>