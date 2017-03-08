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

      userId: null,

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

    beforeRouteEnter(to, from, next) {
      next(innovationsComponent => {
        innovationsComponent.init(to.params.userId);
      });
    },

    beforeRouteUpdate(to, from, next) {
      this.init(to.params.id);
      next();
    },

    data() {
      return defaultInnovationsData();
    },

    created() {
      this.loadInnovations();
    },

    methods: {

      init(userId) {
        this.userId = userId;
        this.loadInnovations();
      },

      // Request innovations
      loadInnovations() {
        let url = this.userId ? '/api/users/' + this.userId + '/innovations' : '/api/innovations';
        axios.get(url)
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