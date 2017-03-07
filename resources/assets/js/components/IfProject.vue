<template>
  <div class="project">

    <if-message ref="message"></if-message>

    <div v-if="details" class="row">
      <div v-if="details" id="projectDetails" class="col-sm-6" style="max-height: 33vh; overflow: auto;">
        <h1>{{details.name}}</h1>
        <a v-if="className" href="#" v-on:click="selectClass($event)">{{className}}</a>
        <p v-if="details.description">{{details.description}}</p>
      </div>

      <div id="members" class="col-sm-6" style="max-height: 33vh; overflow: auto;">
        <h2>Members</h2>
          <if-card v-for="member in details.members">
            <span class="glyphicon glyphicon-user"></span>
            <b>{{member.username}}</b>
          </if-card>
      </div>
    </div>

    <div style="margin-top: 25px;" class="row">
      <div class="col-md-12">      
        <ul id="my-tabs" class="nav nav-tabs">
          <li class="active"><a v-on:click="loadMetrics()" data-toggle="tab" href="#metrics">Metrics</a></li>
          <li><a data-toggle="tab" href="#commits">Commits</a></li>
          <li><a data-toggle="tab" href="#codeReviews">Code Review</a></li>
        </ul>
        
        <br>

        <div class="tab-content" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;">
          
          <div id="metrics" class="tab-pane fade in active">
            <div v-show="details" id="project-metrics">              
              <div class="row">
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Code Review</span>
                    <span class="pull-right">(1st /13)</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="codeReviewChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Feedback</span>
                    <span class="pull-right">(1st /13)</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="feedbackChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Commit Balance</span>
                    <span class="pull-right">(1st /13)</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="commitBalanceChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
              </div>
            </div>
          </div>

          <div id="commits" class="tab-pane fade">
            <div v-for="(commit, index) in commits" v-on:click="addCommitForReview(index)">
              <if-card :style="seletableStyle(index)">
                <a :href="commit.commit_url" class="pull-right">{{commit.commit_url}}</a>
                <span class="h3">{{commit.comment}}</span>                            
                <br>by {{commit.commiter.username}} ({{commit.date}})
              </if-card>
            </div>
          </div>

          <div id="codeReviews" class="tab-pane fade">
            <div style="padding: 0px;" class="col-md-3">             
              <if-card v-if="createReview">      
                <b>Title</b><br><input v-model="newTitle" style="width: 90%;" type="text" placeholder="10+ chars"><br>
                <b>Description</b><br><textarea v-model="newDescription" style="width: 90%; height: 50%;" placeholder="20+ chars"></textarea><br>
                <span class="pull-right" style="margin-right: 10%;">
                  <a v-if="selectingCommits" href="#" v-on:click="submitCodeReview($event)">[+] Create</a>
                  <a v-else href="#" v-on:click="selectCommits($event)">[+] Commits</a>
                  <a style="color: red;" href="#" v-on:click="setCreateReview($event, false)">[-] Cancel</a>
                </span>                    
              </if-card>
              <div v-else v-on:click="setCreateReview($event, true)">
                <if-card>
                  <h2>[+] Create</h2>
                </if-card>
              </div>              
            </div>
            <div v-for="codeReview in codeReviews" style="padding: 0px;" class="col-md-3">
              <if-card>
                <p class="pull-right">{{codeReview.date}}</p>
                <h3>{{codeReview.title}}</h3>
                <p>{{codeReview.description}}</p>
                <p>{{codeReview.id}}</p>
                <b>{{codeReview.owner ? codeReview.owner.username : ''}}</b>
              </if-card>
              <if-comments :projectID="id" :codeReviewID="codeReview.id"></if-comments>
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
  import IfComments from './IfComments.vue'

  function defaultProjectData() {
    return {
      id: '',
      details: null,
      className: '',
      metrics: null,
      codeReviews: null,
      createReview: false,
      newTitle: '',
      newDescription: '',
      newCommits: [],
      commits: null
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    components: {
      IfCard,
      IfMessage,
      IfComments
    },

    data() {
      return defaultProjectData();
    },
    
    beforeRouteEnter(to, from, next) {
      console.log("project beforeRouteEnter called");
      next(projectComponent => {
        projectComponent.init(to.params.id);
      });
    },

    beforeRouteUpdate (to, from, next) {
      if (to.params.id !== this.id) {
        this.init(to.params.id);
      }
      next();
    },

    computed: {
      validCodeReviewFields() {
        if (!this.newTitle || this.newTitle.length < 10) {
          this.$refs.message.display('Title must be at least 10 characters.');
          return false;
        }
        if (!this.newDescription || this.newDescription.length < 20) {
          this.$refs.message.display('Description must be at least 20 characters.');
          return false;
        }
        if (!this.newCommits || this.newCommits.length < 1) {
          this.$refs.message.display('Please select at least one commit for review.');
          return false;
        }
        return true;
      }
    },

    methods: {
      init(id) {
        this.id = id;
        this.loadDetails();
      },

      seletableStyle(index) {
        let o = this.newCommits.indexOf(index) >= 0 ? 0.5 : 1;
        return {
          opacity: o
        };
      },

      loadMetricsAfter(delay) {
        setTimeout(this.loadMetrics, delay);
      },

      loadMetrics() {
        this.metrics = [];
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
          axios.get('api/projects/' + this.id)
            .then(res => {
              this.details = res.data;
              this.loadClassName();
              this.loadCommits();
              this.loadCodeReviews();
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

      loadCommits() {
        axios.get('api/projects/' + this.id + '/commits')
          .then((res) => {
            this.commits = res.data.commits;
          })
          .catch((error) => {
            console.log(error);
            console.log('Failed to load commits');
          });
      },

      loadCodeReviews() {
        axios.get('api/projects/' + this.id + '/codereviews')
          .then((res) => {
            this.codeReviews = res.data;
          })
          .catch((error) => {
            console.log(error);
            console.log('Failed to load code reviews');
          });
      },

      loadClassName() {
        if (this.details && this.details.classId) {
          axios.get('api/classes/' + this.details.classId)
            .then(res => {
              this.className = res.data.code;                    
            })
            .catch(error => {
              console.log(error);
              console.log('Failed to load class details');
            });
        }
      },

      setCreateReview(e, active) {
        e.preventDefault();
        this.resetNewReviewData();        
        this.createReview = active;
      },

      resetNewReviewData() {
        this.newTitle = this.newDescription = '';
        this.newCommits = [];
        this.selectingCommits = false;
      },

      selectCommits(e) {
        e.preventDefault();
        this.selectingCommits = true;
        $('#my-tabs a[href="#commits"]').tab('show');     
      },

      addCommitForReview(index) {
        if (this.selectingCommits) {
          let existing = this.newCommits.indexOf(index);
          if (existing >= 0) {
            this.newCommits.splice(existing, 1);
          }
          else {
            this.newCommits.push(index);
          }          
        }        
      },

      submitCodeReview(e) {
        e.preventDefault();
        if (this.validCodeReviewFields) {
          let commitIds = "";
          for (let i = 0; i < this.newCommits.length - 1; i++) {
            let commitIndex = this.newCommits[i];
            commitIds += this.commits[commitIndex].id + ','
          }
          commitIds += this.commits[this.newCommits[this.newCommits.length - 1]].id;

          axios.post('api/projects/' + this.id + '/codereviews', { title: this.newTitle, description: this.newDescription, commitIds })
            .then((res) => {
              console.log('Succeeded in submitting code review.');
            })
            .catch((error) => {
              console.log(error);
              console.log('Failed to submit code review.');
            });

          this.setCreateReview(e, false);
        }        
      },

      resetData() {
        Object.assign(this.$data, defaultProjectData());
      },

      selectClass(e) {
        e.preventDefault();
        this.$router.push('/dashboard/classes/' + this.details.classId);
      }
    }

  }
</script>

<style scoped>
  #codeReviews .if-card {
    width: 90%;
    height: 20vw;
    min-height: 200px;
    overflow: hidden;
    padding: 15px;
  }  

  .project .if-card {
    margin: 10px;    
  }
  
  #project-metrics .if-card {
    width: 90%;
    height: 20vw;
    min-height: 200px;
    overflow: hidden;
    padding: 15px;
  }

  #commits .if-card {
    display: block;
    width: 90%;
    overflow: hidden;
    margin-bottom: 15px;
  }
</style>