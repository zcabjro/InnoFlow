<template>
  <div>
    <loading v-if="loading"></loading>
    <if-card>
      <h1>Welcome to InnoFlow!</h1>
      <ul>
        <h4>
          <li>Project transparency</li>
        </h4>
        <h4>
          <li>Feedback included</li>
        </h4>
        <h4>
          <li>Innovation showcased</li>
        </h4>
      </ul>
    </if-card>
  </div>  
</template>

<script>
  import Loading from './Loading.vue'
  import IfCard from './IfCard.vue'

  export default {
    name: 'landing',
    
    data() {
      return {
        loading: false
      }
    },

    components: {
      Loading,
      IfCard
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.checkWaitForToken();
      });
    },

    methods: {
      checkWaitForToken() {
        this.loading = window.waitForToken;
        window.waitForToken = false;
      }
    }
  }
</script>

<style></style>