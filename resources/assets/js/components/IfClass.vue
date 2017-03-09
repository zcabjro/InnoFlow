<template>
  <div class="class" >

    <if-message ref="message"></if-message>

    <div v-if="details" class="row">
      <div v-if="details" id="projectDetails" class="col-sm-6" style="max-height: 33vh; overflow: auto;">
        <h1>{{details.code}}: {{details.name}}</h1>
        <p v-if="details.description">{{details.description}}</p>
      </div>

      <div id="admins" class="col-sm-6" style="max-height: 33vh; overflow: auto;">
        <h2>Admins</h2>
          <if-card v-for="admin in details.admins">
            <span class="glyphicon glyphicon-user"></span>
            <b>{{admin.username}}</b>
          </if-card>
      </div>
    </div>

    <div style="margin-top: 25px;" class="row">
      <div class="col-md-12">      
        <ul id="my-tabs" class="nav nav-tabs">
          <li class="active"><a v-on:click="loadMetrics()" data-toggle="tab" href="#metrics">Metrics</a></li>
          <li><a data-toggle="tab" href="#projects">Projects</a></li>
        </ul>
        
        <br>

        <div class="tab-content" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;">
          
          <div id="metrics" class="tab-pane fade in active">
            <div v-show="details" id="class-metrics">              
              <div class="row">
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Code Review</span>                    
                    <div style="width: 100%; height: 80%;">
                      <canvas id="codeReviewChart" width="200" height="200"></canvas>
                    </div>
                    <span v-show="averageCodeReviews !== null" class="pull-right">Avg. Code Reviews: {{averageCodeReviews}}</span>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Feedback</span>                    
                    <div style="width: 100%; height: 80%;">
                      <canvas id="feedbackChart" width="200" height="200"></canvas>
                    </div>
                    <span v-show="averageComments !== null" class="pull-right">Avg. Comments: {{averageComments}}</span>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Commit Balance</span>                    
                    <div style="width: 100%; height: 80%;">
                      <canvas id="commitBalanceChart" width="200" height="200"></canvas>
                    </div>
                    <span v-show="averageCommitBalance !== null" class="pull-right">Avg. Commit Balance: {{averageCommitBalance}}</span>
                  </if-card>
                </div>
              </div>
            </div>
          </div>

          <div v-if="details" id="projects" class="tab-pane fade">
            <div v-for="project in details.projects">
              <if-card>
                <a href="#" v-on:click="selectProject($event, project.id)"><span class="h3">{{project.name}}</span></a>
              </if-card>
            </div>
          </div>
        </div>
      </div>      
    </div>    
  </div>
</template>

<script>
  import IfCard from './IfCard.vue'
  import IfMessage from './IfMessage.vue'
  import Chart from 'chart.js'

  function defaultClassData() {
    return {
      id: '',
      details: null,
      averageCodeReviews: null,
      averageComments: null,
      averageCommitBalance: null
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-class',

    components: {
      IfCard,
      IfMessage     
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
      },

      loadMetricsAfter(delay) {
        setTimeout(this.loadMetrics, delay);
      },

      loadMetrics() {
        if (this.id) {
          axios.get('api/classes/' + this.id + '/metrics')
            .then((res) => {
              console.log(res);
              this.loadCodeReviewMetric(res.data.codeReviewMetric);
              this.loadFeedbackMetric(res.data.feedbackMetric);
              this.loadCommitBalanceMetric(res.data.commitBalanceMetric);
            })
            .catch((error) => {
              console.log(error);
              console.log('Failed to load metric data');
            });
        }       
      },

      loadCodeReviewMetric(metric) {
        this.averageCodeReviews = metric.averageValidCodeReviews;

        // Draw chart
        let canvas = document.getElementById('codeReviewChart');

        let codeReviewChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractProjectData(metric, '% Code Reviews'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      loadFeedbackMetric(metric) {
        this.averageComments = metric.totalFeedback;

        // Draw chart
        let canvas = document.getElementById('feedbackChart');
        let feedbackChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractProjectData(metric, '% Comments'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      loadCommitBalanceMetric(metric) {
        this.averageCommitBalance = metric.averageCommitBalance;

        // Draw chart
        let canvas = document.getElementById('commitBalanceChart');
        let commitBalanceChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractProjectData(metric, '% Commits'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      extractProjectData(metric, label) {
        let labels = [];

        // Single dataset
        let dataset = {
          label: label,
          data: []
        };

        for (let i = 0; i < metric.projectLevel.length; i++) {
          labels.push(metric.projectLevel[i].name);
          dataset.data.push(metric.projectLevel[i].contribution);
        }
        return {
          labels,
          datasets: [dataset]
        };
      },

      loadDetails() {
        if (this.id) {
          axios.get('api/classes/' + this.id)
            .then(res => {
              this.details = res.data;

              // Load metrics after short delay (for animation)
              this.loadMetricsAfter(300);              
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

      selectProject(e, projectId) {
        e.preventDefault();
        this.$router.push('/dashboard/projects/' + projectId);
      }
    }

  }
</script>

<style scoped>
  .class .if-card {
    margin: 10px;    
  }
  
  #class-metrics .if-card {
    width: 90%;
    height: 20vw;
    min-height: 200px;
    overflow: hidden;
    padding: 15px;
  }
</style>