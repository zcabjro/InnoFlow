<template>
  <div class="row class">
    <div v-if="details" class="col-md-8">
      <h1>{{details.code}}: {{details.name}}</h1>
      <p v-if="details.description">{{details.description}}</p>      

      <div id="admins">
        <h2>Admins</h2>
        <if-card v-for="admin in details.admins">
          <span class="glyphicon glyphicon-user"></span>
          <b>{{admin.email}}</b>
        </if-card>
      </div>

      <div id="projects">
        <h2>Projects</h2>
        <a href="#" v-for="project in details.projects" v-on:click="selectProject($event, project)">
          <if-card>
            <span class="glyphicon glyphicon-th-list"></span>
            <b>{{project.name}}</b>
          </if-card>
        </a>
      </div>
    </div>

    <div v-show="details && metrics" class="col-md-4">
      <div id="class-metrics">
        <h2>Class Data</h2>
        <if-card>
          <span class="h3">Code Review</span><span class="pull-right">(1st /13)</span>
          <div class="col-md-12" style="height: 80%;">
            <canvas id="codeReviewChart" width="200" height="200"></canvas>
          </div>
        </if-card>
        <if-card>
          <span class="h3">Feedback</span><span class="pull-right">(2nd /13)</span>
          <div class="col-md-12" style="height: 80%;">
            <canvas id="feedbackChart" width="200" height="200"></canvas>
          </div>
        </if-card>
        <if-card>
          <span class="h3">Commit Activity</span><span class="pull-right">(1st /13)</span>
          <div class="col-md-12" style="height: 80%;">
            <canvas id="commitActivityChart" width="200" height="200"></canvas>
          </div>
        </if-card>
      </div>      
    </div>
  </div>
</template>

<script>
  import IfCard from './IfCard.vue' // Card component for displaying projects
  import Chart from 'chart.js' // Charts used for metric visualisation

  function defaultClassData() {
    return {
      id: '',
      details: null,
      metrics: []
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    components: {
      IfCard
    },

    data() {
      return defaultClassData();
    },
    
    beforeRouteEnter(to, from, next) {
      next(classComponent => {
        classComponent.init(to.params.id);
      });
    },

    beforeRouteUpdate (to, from, next) {
      if (to.params.id !== this.id) {
        this.init(to.params.id);              
      }
      next();
    },

    methods: {
      init(id) {
        this.id = id;
        this.loadDetails();
        this.loadMetrics();
      },

      loadMetrics() {
        let codeReviewChartId = 'codeReviewChart';
        var myChart = new Chart(codeReviewChartId, {
          type: 'bar',
          data: {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
          },
          options: {
            maintainAspectRatio: false,
            scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
            }
          }
        });
      },

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
      },

      selectProject(e, project) {
        e.preventDefault();
        let url = '/dashboard/projects/' + project.id;
        console.log(url);
        this.$router.push(url);
      }
    }

  }
</script>

<style scoped>
  #class-metrics {
    height: 90vh;
    overflow-y: auto;
    overflow-x: auto;    
  }

  .class .if-card {
    margin-right: 1vw;
    margin-bottom: 1vw;
  }

  #class-metrics > *.if-card {
    width: 80%;
    height: 33vh;
    margin-right: 0;
  }
</style>