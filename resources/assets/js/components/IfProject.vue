<template>
  <div class="project" >

    <if-message ref="message"></if-message>

    <div v-if="details" class="row">
      <div v-if="details" id="projectDetails" class="col-sm-6" style="max-height: 33vh; overflow: auto;">
        <h1>{{details.name}}</h1>
        <p v-if="details.class && details.class.name">Class: {{details.class.name}}</p>
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

        <div class="tab-content" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;"   @click="close()">
          
          <div id="metrics" class="tab-pane fade in active">
            <div v-show="details" id="project-metrics">              
              <div class="row">
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Code Review</span>
                    <span v-show="validCodeReviews !== null" class="pull-right">Code Reviews: {{validCodeReviews}}</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="codeReviewChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Feedback</span>
                    <span v-show="comments !== null" class="pull-right">Comments: {{comments}}</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="feedbackChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div class="col-md-4">
                  <if-card>
                    <span class="h3">Commit Balance</span>
                    <span v-show="averageCommitBalance !== null" class="pull-right">Avg. Commit Balance: {{averageCommitBalance}}</span>
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
                <a :href="commit.commit_url" target="_blank" class="pull-right">{{commit.commit_url}}</a>
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
              <div @click="toggleModal(codeReview.id)" @click.stop style="position: absolute; left: 48%; top: 50%; transform: translate(-50%, -50%);width: 90%; height: 92%;">
                <div style = "margin:14px; width: 91%; height: 87%; overflow: hidden;">
                <p class="pull-right">{{codeReview.date}}</p>
                <h3>{{codeReview.title}}</h3>
                <p>{{codeReview.description}}</p> 
                <b>{{codeReview.owner ? codeReview.owner.username : ''}}</b>
                </div>
                </div>
              </if-card> 
              <if-modal-box :showModal="showModal" v-if="showModal[codeReview.id]">
              <if-comments :projectID="id" :codeReviewID="codeReview.id"></if-comments></if-modal-box>
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
  import IfModalBox from './IfModalBox.vue'
  import Chart from 'chart.js'

  function defaultProjectData() {
    return {
      id: '',
      details: null,
      className: '',
      codeReviews: null,
      createReview: false,
      newTitle: '',
      newDescription: '',
      newCommits: [],
      commits: null,
      showModal: [],
      
      
      validCodeReviews: null,
      comments: null,
      averageCommitBalance: null
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    components: {
      IfCard,
      IfMessage,
      IfComments,
      IfModalBox
    },

    data() {
      return defaultProjectData();
    },
    
    beforeRouteEnter(to, from, next) {
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
      close(){        
        this.showModal=[];
      },
      init(id) {
        this.id = id;
        this.loadDetails();
      },

      toggleModal(index) {
        this.showModal[index] = !this.showModal[index];
        this.showModal = [...this.showModal]; 
      },

      seletableStyle(index) {
        let c = this.newCommits.indexOf(index) >= 0 ? '#af0f0f' : 'black';
        return {
          color: c
        };
      },

      loadMetricsAfter(delay) {
        setTimeout(this.loadMetrics, delay);
      },

      loadMetrics() {
        if (this.id) {
          axios.get('api/projects/' + this.id + '/metrics')
            .then((res) => {
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
        this.validCodeReviews = metric.totalValidCodeReviews;

        // Draw chart
        let canvas = document.getElementById('codeReviewChart');

        let codeReviewChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractIndividualData(metric, '% Code Reviews'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      loadFeedbackMetric(metric) {
        this.comments = metric.totalFeedback;

        // Draw chart
        let canvas = document.getElementById('feedbackChart');
        let feedbackChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractIndividualData(metric, '% Comments'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      loadCommitBalanceMetric(metric) {
        this.averageCommitBalance = Math.round(metric.averageCommitBalance * 100);

        // Draw chart
        let canvas = document.getElementById('commitBalanceChart');
        let commitBalanceChart = new Chart(canvas, {
          type: 'doughnut',
          data: this.extractIndividualData(metric, '% Commits'),
          options: {
            maintainAspectRatio: false
          }
        });
      },

      extractIndividualData(metric, label) {
        let labels = [];

        // Single dataset
        let dataset = {
          label: label,
          data: []
        };

        for (let i = 0; i < metric.individualLevel.length; i++) {
          labels.push(metric.individualLevel[i].username);
          dataset.data.push(Math.round(metric.individualLevel[i].contribution * 100));
        }
        return {
          labels,
          datasets: [dataset]
        };
      },

      loadDetails() {
        if (this.id) {
          axios.get('api/projects/' + this.id)
            .then(res => {
              this.details = res.data;
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
              this.codeReviews.unshift(res.data);
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