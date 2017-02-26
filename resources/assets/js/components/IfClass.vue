<template>
  <div>
    <h1>Details for Class {{id}}</h1>
    <div v-if="details">
      <p>Name: {{details.name}}</p>
      <p>Code: {{details.code}}</p>
      <p v-if="details.description">Description: {{details.description}}</p>
      <p>members: {{details.admins}}</p>
    </div>
  </div>
</template>

<script>

  function defaultClassData() {
    return {
      id: '',
      details: null
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    data() {
      return defaultClassData();
    },
    
    beforeRouteEnter(to, from, next) {
      next(classComponent => {
        classComponent.id = to.params.id;
        classComponent.loadDetails();
      });
    },

    beforeRouteUpdate (to, from, next) {
      if (to.params.id !== this.id) {
        this.id = to.params.id;
        this.loadDetails();
      }

      next();
    },

    methods: {
      
      loadDetails() {
        if (this.id) {
          axios.get('api/classes/' + this.id)
            .then(res => {
              console.log(res);
              this.details = res.data;
            })
            .catch(error => {
              console.log(error);
              console.log('Failed to load details');
              this.resetData();
            });
        }
        else {
          this.resetData();
        }
      },

      resetData() {
        Object.assign(this.$data, defaultClassData());
      }
    }

  }
</script>

<style scoped>
</style>