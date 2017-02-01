<template>
  <!-- Landing page content -->
  <div id="landing">

    <!-- Card about InnoFlow -->
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
  import IfCard from './IfCard.vue' // Card component used to display info

  export default {
    // Debug name and html tag of this component
    name: 'landing',

    // Components used by this component
    components: {
      IfCard
    }
  }
</script>

<style></style>