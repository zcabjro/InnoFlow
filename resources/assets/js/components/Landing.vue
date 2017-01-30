<template>
  <loading v-if="loading"></loading> 
  <div v-else class="info">
    <h1>Welcome to InnoFlow!</h1>
    <ul>
      <h4><li>Project transparency</li></h4>
      <h4><li>Feedback included</li></h4>
      <h4><li>Innovation showcased</li></h4>
    </ul>
  </div>
</template>

<script>
  import Loading from './Loading.vue'

  export default {
    name: 'landing',
    data() {
      return {
        loading: false
      }
    },
    components: {
      Loading
    },
    created: function() {
      this.checkWaitForToken();
    },
    watch: {
      '$route': 'checkWaitForToken'
    },
    methods: {
      checkWaitForToken() {
        this.loading = window.waitForToken;
        window.waitForToken = false;
        console.log('loading: ' + this.loading);
      }
    }
  }
</script>

<style>
  .info {
    display: inline-block;
    padding: 15px;
    background-color: #e7e7e7;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>
