<template>
  <div>
    <h1>Details for Project {{id}}</h1>
    <div v-if="details">
      <p>Name: {{details.name}}</p>
      <p>Owned: {{details.isOwner ? true : false}}</p>
      <p v-if="details.classId">Class ID: {{details.classId}}</p>
      <p v-if="details.description">Description: {{details.description}}</p>
      <p>members: {{details.members}}</p>
    </div>
  </div>
</template>

<script>

  function defaultProjectData() {
    return {
      id: '',
      details: null
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    data() {
      return defaultProjectData();
    },
    
    beforeRouteEnter(to, from, next) {
      next(project => {
        project.id = to.params.id;
        project.loadDetails();
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
          axios.get('api/projects/' + this.id)
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
        Object.assign(this.$data, defaultProjectData());
      }
    }

  }
</script>

<style scoped>
</style>