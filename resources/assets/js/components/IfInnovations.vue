<template>
  <div v-show="highlighted" class="col-lg-12">
    <h1>Innovations</h1>              
    <div v-for="i in innovations">
      <p>{{i.created}}</p>
      <vue-markdown :source="i.code"></vue-markdown>
    </div>            
  </div>
</template>

<script>
  import VueMarkdown from 'vue-markdown' // Markdown display component

  // Helper for setting default IfInnovations data
  function defaultInnovationsData() {
    return {

      // Whether innovations have been syntax highlighted or not
      highlighted: false,

      // Innovation markup snippets
      innovations: []
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',
    
    // Components used by IfInnovations
    components: {
      VueMarkdown
    },

    data() {
      return defaultInnovationsData();
    },

    created() {
      this.loadInnovations();
    },

    methods: {
      // Request innovations
      loadInnovations() {
        axios.get('/api/innovations')
          .then((res) => {
            this.highlighted = false;
            this.innovations = res.data;  
            setTimeout(() => {
              window.Prism.highlightAll();
              this.highlighted = true;
            }, 300);         
          })
          .catch((error) => {
            console.log('Error loading innovations');
          });
      }
    }
  }
</script>

<style scoped>
</style>