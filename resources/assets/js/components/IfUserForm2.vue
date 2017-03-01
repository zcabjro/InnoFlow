<template>
  <!-- Form -->
  <form class="form-horizontal">
    <fieldset> 
      <!-- FormField components -->
      <if-form-field-two-line v-for="(field, index) in fields" v-model="field.value" :label="field.label" :type="field.type" :placeholder="field.placeholder" :format="field.format"></if-form-field>
    </fieldset>    
  </form>
</template>

<script>
  import IfFormFieldTwoLine from './IfFormFieldTwoLine.vue' // Form field component used for this form's fields

  export default {
    // Debug name and html tag of this component
    name: 'if-user-form',
    
    // Components used by this component
    components: {
      IfFormFieldTwoLine
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