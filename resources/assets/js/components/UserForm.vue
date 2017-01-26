<template>
  <form class="form-horizontal">
    <fieldset>
      <!--Legend -->
      <legend>{{legend}}</legend>
      <!-- Fields -->
      <form-field v-for="(field, index) in fields" v-model="field.value" :label="field.label" :type="field.type" :placeholder="field.placeholder"></form-field>
    </fieldset>
  </form>
</template>

<script>
  import FormField from './FormField.vue'

  export default {
    name: 'user-form',
    
    components: {
      FormField
    },
    
    props: [
      'legend',
      'fields' 
    ]
  }
</script>

<style>
  legend {
    text-align: center;
  }
</style>