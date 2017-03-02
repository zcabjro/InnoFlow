<template>
  <!-- Very simple, styled container that displays slot contents -->
  <div class="if-card">
    <slot></slot>
  </div>
</template>

<script>
  export default {
    // Debug name and html tag of this component
    name: 'if-card'
  }
</script>

<style scoped>
  .if-card {
    display: inline-block;
    padding: 15px;
    background-color: #f1f1f1;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.19);
  }
</style>