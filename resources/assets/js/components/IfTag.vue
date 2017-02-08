<template>
  <span>{{label}} <a href="javascript:void(0)"><span class="badge">X</span></a></span>
</template>

<script>
  export default {
    name: 'if-tag',

    data() {
      return {        
      }
    },

    props: [
      'label'
    ]
  }
</script>

<style></style>