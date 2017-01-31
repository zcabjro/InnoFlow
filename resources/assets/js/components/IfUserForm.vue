<template>
  <!-- Form -->
  <form class="form-horizontal">
    <fieldset>
      <!--Legend -->
      <legend>{{legend}}</legend>
      <!-- FormField components -->
      <if-form-field v-for="(field, index) in fields" v-model="field.value" :label="field.label" :type="field.type" :placeholder="field.placeholder"></if-form-field>
    </fieldset>
  </form>
</template>

<script>
  import IfFormField from './IfFormField.vue' // Form field component used for this form's fields

  export default {
    // Debug name and html tag of this component
    name: 'if-user-form',
    
    // Components used by this component
    components: {
      IfFormField
    },
    
    // Properties supplied by the parent component
    props: [
      // Form title
      'legend',
      // Field data
      'fields' 
    ]
  }
</script>

<style scoped>
  legend {
    text-align: center;
  }
</style>