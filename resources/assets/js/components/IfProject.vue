<template>
  <div class="project">
    <div v-if="details" class="row">
      <div v-if="details" id="projectDetails" class="col-xs-6" style="max-height: 33vh; overflow: auto;">
        <h1>{{details.name}}</h1>
        <a v-if="className" href="#" v-on:click="selectClass($event)">{{className}}</a>
        <p v-if="details.description">{{details.description}}</p>
      </div>

      <div id="members" class="col-xs-6" style="max-height: 33vh; overflow: auto;">
        <h2>Members</h2>
          <if-card v-for="member in details.members">
            <span class="glyphicon glyphicon-user"></span>
            <b>{{member.username}}</b>
          </if-card>
      </div>
    </div>

    <div style="margin-top: 25px;" class="row">
      <div class="col-md-12">      
        <ul class="nav nav-tabs">
          <li class="active"><a v-on:click="loadMetrics()" data-toggle="tab" href="#metrics">Metrics</a></li>
          <li><a data-toggle="tab" href="#commits">Commits</a></li>
          <li><a data-toggle="tab" href="#codeReview">Code Review</a></li>
        </ul>
        <br>
        <div class="tab-content" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;">
          <div id="metrics" class="tab-pane fade in active">
            <div v-show="details" id="project-metrics">              
              <div class="row">
                <div style="padding: 15px" class="col-md-4">
                  <if-card>
                    <span class="h3">Code Review</span>
                    <span class="pull-right">(1st /13)</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="codeReviewChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div style="padding: 15px" class="col-md-4">
                  <if-card>
                    <span class="h3">Feedback</span>
                    <span class="pull-right">(1st /13)</span>
                    <div style="width: 100%; height: 80%;">
                      <canvas id="feedbackChart" width="200" height="200"></canvas>
                    </div>
                  </if-card>
                </div>
                <div style="padding: 15px" class="col-md-4">
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
            <if-card v-for="commit in commits">
              <a :href="commit.commit_url" class="pull-right">{{commit.commit_url}}</a>
              <span class="h3">{{commit.comment}}</span>                            
              <br>by {{commit.commiter}} ({{commit.date}})
            </if-card>
          </div>
          <div id="codeReview" class="tab-pane fade">
            <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
          </div>
        </div>
      </div>      
    </div>    
  </div>
</template>

<script>
  import IfCard from './IfCard.vue'

  function defaultProjectData() {
    return {
      id: '',
      details: null,
      className: '',
      metrics: null,
      commits: [{"id":"5161f9f9f880285f350c0d1dc1abac72a4f41a57","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/5161f9f9f880285f350c0d1dc1abac72a4f41a57","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"483789474858dc78ee79ef3c40a5881990196472","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/483789474858dc78ee79ef3c40a5881990196472","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a5e229054786b8b7f3610d0935c50275e2375d31","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a5e229054786b8b7f3610d0935c50275e2375d31","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"10cd7905ecb31cea5f652b1324a206afab3dba55","comment":"model not found exception","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/10cd7905ecb31cea5f652b1324a206afab3dba55","changes":{"adds":0,"edits":3,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"8ac39bf31faebe4746fa2d33aa729d44531917da","comment":"commit endpoints","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/8ac39bf31faebe4746fa2d33aa729d44531917da","changes":{"adds":2,"edits":21,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a45391649dc23d7e04ca31d061a6eadeb38f0c74","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a45391649dc23d7e04ca31d061a6eadeb38f0c74","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a610bcc858bf88a0d4751b78d466b3a1ad114133","comment":"Updated Register component to include username","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a610bcc858bf88a0d4751b78d466b3a1ad114133","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"e6109a36a3e18a7a57c6f91049fe67d3a60098ba","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/e6109a36a3e18a7a57c6f91049fe67d3a60098ba","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"6fa8bb3ece3ca020a5f1d7b754d9f6aff79c5bce","comment":"Removed metric titles","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/6fa8bb3ece3ca020a5f1d7b754d9f6aff79c5bce","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"67cb40fe9d1fb3e398f65caf1b59472ba0804c7e","comment":"app.js","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/67cb40fe9d1fb3e398f65caf1b59472ba0804c7e","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"0fd858663503631e636d6aeaf474eca175c0bfa8","comment":"Delay the loading of metrics for better display and animation","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/0fd858663503631e636d6aeaf474eca175c0bfa8","changes":{"adds":2,"edits":7,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"98ed2cd292e2a0b16aaa8de8e56176c8addf761c","comment":"app.js","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/98ed2cd292e2a0b16aaa8de8e56176c8addf761c","changes":{"adds":0,"edits":12,"deletes":2},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"3702269cf66fa69dd122f97ea5fe08d252cf45ee","comment":"replaced v-if for v-show in metric display","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/3702269cf66fa69dd122f97ea5fe08d252cf45ee","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"62b2ffa2681b7b23ba184efd94e7fc93b964442d","comment":"Merge branch 'master' of github.com:zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/62b2ffa2681b7b23ba184efd94e7fc93b964442d","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"ca55341913f53a9071f51b4a6c5c216f09334ce6","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/ca55341913f53a9071f51b4a6c5c216f09334ce6","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"fc50ea46946ef3b093a2bb65b8c7e106795baddb","comment":"Updated project and class display with styles, links and dummy metrics","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/fc50ea46946ef3b093a2bb65b8c7e106795baddb","changes":{"adds":0,"edits":13,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"d42497d7665b68f750b2a3cec3a055da57636517","comment":"storeCommit VstsApiService","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/d42497d7665b68f750b2a3cec3a055da57636517","changes":{"adds":3,"edits":17,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"bd14385c1bc79d61579a018afb1cf8ad2a27450b","comment":"refresh projects index","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/bd14385c1bc79d61579a018afb1cf8ad2a27450b","changes":{"adds":1,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"7aa2dd0227b89b695b62f5497d3ab6c7901379ea","comment":"isLoggedIn improved","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/7aa2dd0227b89b695b62f5497d3ab6c7901379ea","changes":{"adds":0,"edits":4,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"187b334511d8159ee0937d62c6bfa76bf38a0f97","comment":"isLoggedIn bug fix revisited","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/187b334511d8159ee0937d62c6bfa76bf38a0f97","changes":{"adds":0,"edits":4,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"e26eea50ac1b4d03fcf92c05bf3cbc6a5cc87282","comment":"isLoggedIn bug fix","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/e26eea50ac1b4d03fcf92c05bf3cbc6a5cc87282","changes":{"adds":0,"edits":7,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"37225845bfdc67ccc3595933df5966f9ff71937c","comment":"Merge branch 'master' of github.com:zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/37225845bfdc67ccc3595933df5966f9ff71937c","changes":{"adds":0,"edits":11,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"cc1a2da79ebbbe13b0b92557bf108dd6cc86d196","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/cc1a2da79ebbbe13b0b92557bf108dd6cc86d196","changes":{"adds":2,"edits":13,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"2f771667972cadba4c9a9c969fbb30fa2ff97165","comment":"Separated dropdown from search","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/2f771667972cadba4c9a9c969fbb30fa2ff97165","changes":{"adds":1,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a70a562fabcf0c3a18343da3aa94801093a3a746","comment":"Refactored dropdown","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a70a562fabcf0c3a18343da3aa94801093a3a746","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"5161f9f9f880285f350c0d1dc1abac72a4f41a57","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/5161f9f9f880285f350c0d1dc1abac72a4f41a57","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"483789474858dc78ee79ef3c40a5881990196472","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/483789474858dc78ee79ef3c40a5881990196472","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a5e229054786b8b7f3610d0935c50275e2375d31","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a5e229054786b8b7f3610d0935c50275e2375d31","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"10cd7905ecb31cea5f652b1324a206afab3dba55","comment":"model not found exception","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/10cd7905ecb31cea5f652b1324a206afab3dba55","changes":{"adds":0,"edits":3,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"8ac39bf31faebe4746fa2d33aa729d44531917da","comment":"commit endpoints","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/8ac39bf31faebe4746fa2d33aa729d44531917da","changes":{"adds":2,"edits":21,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a45391649dc23d7e04ca31d061a6eadeb38f0c74","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a45391649dc23d7e04ca31d061a6eadeb38f0c74","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a610bcc858bf88a0d4751b78d466b3a1ad114133","comment":"Updated Register component to include username","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a610bcc858bf88a0d4751b78d466b3a1ad114133","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"e6109a36a3e18a7a57c6f91049fe67d3a60098ba","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/e6109a36a3e18a7a57c6f91049fe67d3a60098ba","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"6fa8bb3ece3ca020a5f1d7b754d9f6aff79c5bce","comment":"Removed metric titles","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/6fa8bb3ece3ca020a5f1d7b754d9f6aff79c5bce","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"67cb40fe9d1fb3e398f65caf1b59472ba0804c7e","comment":"app.js","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/67cb40fe9d1fb3e398f65caf1b59472ba0804c7e","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"0fd858663503631e636d6aeaf474eca175c0bfa8","comment":"Delay the loading of metrics for better display and animation","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/0fd858663503631e636d6aeaf474eca175c0bfa8","changes":{"adds":2,"edits":7,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"98ed2cd292e2a0b16aaa8de8e56176c8addf761c","comment":"app.js","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/98ed2cd292e2a0b16aaa8de8e56176c8addf761c","changes":{"adds":0,"edits":12,"deletes":2},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"3702269cf66fa69dd122f97ea5fe08d252cf45ee","comment":"replaced v-if for v-show in metric display","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/3702269cf66fa69dd122f97ea5fe08d252cf45ee","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"62b2ffa2681b7b23ba184efd94e7fc93b964442d","comment":"Merge branch 'master' of github.com:zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/62b2ffa2681b7b23ba184efd94e7fc93b964442d","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"ca55341913f53a9071f51b4a6c5c216f09334ce6","comment":"Update README.md","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/ca55341913f53a9071f51b4a6c5c216f09334ce6","changes":{"adds":0,"edits":1,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"fc50ea46946ef3b093a2bb65b8c7e106795baddb","comment":"Updated project and class display with styles, links and dummy metrics","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/fc50ea46946ef3b093a2bb65b8c7e106795baddb","changes":{"adds":0,"edits":13,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"d42497d7665b68f750b2a3cec3a055da57636517","comment":"storeCommit VstsApiService","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/d42497d7665b68f750b2a3cec3a055da57636517","changes":{"adds":3,"edits":17,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"bd14385c1bc79d61579a018afb1cf8ad2a27450b","comment":"refresh projects index","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/bd14385c1bc79d61579a018afb1cf8ad2a27450b","changes":{"adds":1,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"7aa2dd0227b89b695b62f5497d3ab6c7901379ea","comment":"isLoggedIn improved","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/7aa2dd0227b89b695b62f5497d3ab6c7901379ea","changes":{"adds":0,"edits":4,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"187b334511d8159ee0937d62c6bfa76bf38a0f97","comment":"isLoggedIn bug fix revisited","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/187b334511d8159ee0937d62c6bfa76bf38a0f97","changes":{"adds":0,"edits":4,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"e26eea50ac1b4d03fcf92c05bf3cbc6a5cc87282","comment":"isLoggedIn bug fix","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/e26eea50ac1b4d03fcf92c05bf3cbc6a5cc87282","changes":{"adds":0,"edits":7,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"37225845bfdc67ccc3595933df5966f9ff71937c","comment":"Merge branch 'master' of github.com:zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/37225845bfdc67ccc3595933df5966f9ff71937c","changes":{"adds":0,"edits":11,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"cc1a2da79ebbbe13b0b92557bf108dd6cc86d196","comment":"Merge branch 'master' of https:\/\/github.com\/zcabjro\/InnoFlow","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/cc1a2da79ebbbe13b0b92557bf108dd6cc86d196","changes":{"adds":2,"edits":13,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"2f771667972cadba4c9a9c969fbb30fa2ff97165","comment":"Separated dropdown from search","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/2f771667972cadba4c9a9c969fbb30fa2ff97165","changes":{"adds":1,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}},{"id":"a70a562fabcf0c3a18343da3aa94801093a3a746","comment":"Refactored dropdown","date":"2017-03-06 12:00:34+00","commit_url":"https:\/\/zcabjro.visualstudio.com\/_git\/InnoFlow\/commit\/a70a562fabcf0c3a18343da3aa94801093a3a746","changes":{"adds":0,"edits":9,"deletes":0},"commiter":{"id":103,"username":"jedi_jack"}}]
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-project',

    components: {
      IfCard
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

    methods: {
      init(id) {
        this.id = id;
        this.loadDetails();
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
  .project .if-card {
    margin: 10px;    
  }
  
  #project-metrics .if-card {
    width: 90%;
    height: 40vh;
  }

  #commits .if-card {
    display: block;
    width: 90%;
    overflow: hidden;
    margin-bottom: 15px;
  }
</style>