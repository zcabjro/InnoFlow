<template>
  <!-- Animated card conatining slot contents -->
  <div v-bind:class="classList">
    <if-card>
      <p>{{content}}</p>
    </if-card>
  </div>  
</template>

<script>
  import IfCard from './IfCard.vue' // Card component for displaying message

  const defaultClassList = 'if-message hidden-load';

  export default {
    // Debug name and html tag of this component
    name: 'if-message',

    // Components used by this component
    components: {
      IfCard
    },

    // Initialise message data with defaults
    data() {
      return {
        classList: defaultClassList,
        content: '',
        timeout: null
      }
    },

    methods: {
      display(content, duration) {        
        if (content) {
          this.content = content;
          this.classList = defaultClassList + ' animated bounceInDown';
          this.setTimer(duration ? duration : 5000);          
        } 
      },

      setTimer(duration) {
        if (this.timeout !== null) {
          clearTimeout(this.timeout);
          this.timeout = null;
        }

        this.timeout = setTimeout(() => {
          this.classList = defaultClassList + ' animated bounceOutUp';
        }, duration);
      }
    }
  }
</script>

<style>
  .hidden-load {
    visibility: hidden;
  }

  .hidden-load.animated {
    visibility: visible;
  }

  .if-message {
    z-index: 9999;
    position: absolute;
    top: 35px;
    left: 0;
    right: 0;
    margin: 0 auto;
    width: 20%;
    text-align: center;
  }
</style>