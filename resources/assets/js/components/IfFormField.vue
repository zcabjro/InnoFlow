<template>
  <div class="form-group">
    <!-- Field label -->
    <label class="col-md-4 control-label">{{label}}</label>

    <!-- Input area -->
    <div class="col-md-4">
      <input v-if="type !== 'textarea'" ref="input" v-bind:value="value" v-on:input="updateValue($event.target.value)" :type="type" :placeholder="placeholder" class="form-control input-md" required>
      <textarea v-else rows="10" cols="50" v-bind:value="value" v-on:input="updateValue($event.target.value)" :placeholder="placeholder" class="form-control input-md"></textarea>
    </div>
  </div>
</template>

<script>
  export default {
    // Debug name and html tag of this component
    name: 'if-form-field',
    
    // Properties supplied by the parent component
    props: [
      // Label value
      'label',
      // Type of field (<input> type or textarea)
      'type',
      // Placeholder value
      'placeholder',
      // Input value
      'value',
      // Format field input
      'format'
    ],
        
    // Form field component methods
    methods: {
      // Update the value of the field
      updateValue(val) {
        let formattedValue = this.format ? this.format(val) : val;
        
        if (formattedValue !== val) {
          this.$refs.input.value = formattedValue;
        }
        
        // Emits input event for use with v-model
        this.$emit('input', formattedValue);
      }
    }
  }
</script>

<style></style>