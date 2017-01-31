<template>
  <div v-bind:class="classList">
    <if-card>
      <slot></slot>
    </if-card>
  </div>  
</template>

<script>
  import IfCard from './IfCard.vue'

  export default {
    name: 'if-message',

    components: {
      IfCard
    },

    data() {
      return {
        defaultClass: 'if-message hidden-load'
      }
    },

    props: [
      'animated',
      'active'
    ],

    computed: {
      classList() {
        return this.defaultClass + (this.animated ? this.animationClasses : '');
      },
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