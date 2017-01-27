<template>
  <div class="form-group">
    <label class="col-md-4 control-label">{{label}}</label>
    <div class="col-md-4">
      <input ref="input" v-bind:value="value" v-on:input="updateValue($event.target.value)" :type="type" :placeholder="placeholder" class="form-control input-md" required>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'form-field',
    
    props: [
      'label',
      'type',
      'placeholder',
      'value'
    ],
    
    methods: {
      updateValue: function(val) {
        var formattedValue = val.trim();
        
        if (formattedValue !== val) {
          this.$refs.input.value = formattedValue;
        }
        
        this.$emit('input', formattedValue);
      }
    }
  }
</script>

<style>

</style>