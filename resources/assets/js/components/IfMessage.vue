<template>
  <!-- Animated card conatining slot contents -->
  <div v-bind:class="classList">
    <if-card>
      <slot></slot>
    </if-card>
  </div>  
</template>

<script>
  import IfCard from './IfCard.vue' // Card component for displaying message

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
        defaultClass: 'if-message hidden-load'
      }
    },

    // Properties supplied by the parent component
    props: [
      // Whether this message has been animated already or not
      'animated',
      // Whether this message is active and displaying
      'active'
    ],

    // Computed properties
    computed: {
      // CSS classes for the message component
      classList() {
        return this.defaultClass + (this.animated ? this.animationClasses : '');
      },

      // Animation classes for the message component
      animationClasses() {
        return ' animated' + (this.active ? ' bounceInDown' : ' bounceOutUp');
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