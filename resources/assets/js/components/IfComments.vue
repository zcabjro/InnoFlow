<template>             
  <div class="comment">

    <div class="col-lg-12">
      <h1>Review Commits</h1>              
      <div v-for="commit in commits">
        <if-card>
          <h3>{{commit.comment}}</h3>
          <p>by {{commit.commiter.username}} ({{commit.date}})</p>
          <a :href="commit.commit_url" target="_blank">View</a>
        </if-card>
      </div>            
    </div>

    <if-message ref="message"></if-message>
    
    <div class="col-lg-12" id="comments">
      <h1>Comments</h1>       

      <div v-for="i in comments" style="" >
        <if-card> 
          <p >{{i.text}}</p><br>
          <span class="pull-right">by {{i.owner.username}} ({{i.date}})</span>
        </if-card>
      </div> 

      <if-card style="width: 100%;">       
        <b>Message</b><br>
        <textarea v-model="newComment" style="box-sizing:border-box" placeholder="20+ chars"></textarea><br>
        <a href="#" v-on:click="submitComment($event)">Submit</a>  
      </if-card>
    </div>
  
  </div> 
</template>

<script> 
  import IfCard from './IfCard.vue'
  import IfMessage from './IfMessage.vue' 
  function defaultCommentsData() {
    return {
 
      newComment: '',
      commits: [],
 
      comments: [
        {
          "id": 4,
          "date": "2017-03-04 18:13:18",
          "text": "This is a comment. Always make sure a comment is useful.",
          "owner": {
            "id": 101,
            "username": "SickAustrian"
          }
        },
        {
          "id": 3,
          "date": "2017-03-04 17:26:39",
          "text": "This is a comment. Always make sure a comment is useful.",
          "owner": {
            "id": 3,
            "username": "JackRoper"
          }
        },
      ]
    }
  }

  export default {
    // Debug name and html tag of this component
    name: 'if-comments',
    
    components: { 
      IfCard,
      IfMessage
    },

    props: [
      'projectID',
      'codeReviewID' 
    ],

    data() {
      return defaultCommentsData();
    },
 
    beforeRouteEnter(to, from, next) { 
      next(commentsComponent => {
        commentsComponent.init(to.params.id, to.params.codeReviewID);
      }); 
    }, 

    created() { 
      this.loadCommitsAndComments();
    },

    computed: {
      validComment() { 
        if (!this.newComment || this.newComment.length < 20) {
          this.$refs.message.display('Comment must be at least 20 characters.');
          return false;
        } 
        return true;
      }
    },

    methods: {
      init(projectID, codeReviewID) { 
        this.projectID = projectID;
        this.codeReviewID = codeReviewID;
        this.loadDetails(); 
      }, 

      loadDetails() {
        if (this.projectID) {
          axios.get('api/projects/' + this.projectID)
            .then(res => {
              this.details = res.data; 
              this.loadCommitsAndComments();  

            })
            .catch(error => {
              console.log(error);
              console.log('Failed to load details');
              this.resetData();
            });
        }
      },


      loadCommitsAndComments() {  
        axios.get('api/projects/' + this.projectID +'/codereviews/' + this.codeReviewID)
          .then((res) => { 
            this.comments = res.data.comments;
            this.commits = res.data.commits; 

          })
          .catch((error) => {
            console.log(error);
            console.log('Failed to load code review');
          });
      },

      submitComment(e) {
        e.preventDefault();
        if (this.validComment) {  
          axios.post('api/projects/' + this.projectID + '/codereviews/' + this.codeReviewID + '/comments', { 
            projectID: this.projectID, 
            codeReviewId: this.codeReviewID, 
            message: this.newComment 
          })
            .then((res) => {
              this.comments.push(res.data);
            })
            .catch((error) => {
              console.log(error);
              console.log('Failed to submit comment.');
            });

         this.newComment = '';
        }
      }

    }
  }
</script>

<style scoped> 
.if-card {
  margin: 15px;
}

#comments .if-card { 
  height: 50%;
}

textarea {
  resize: none;
  width: 100%;
  height: 200px;
}
</style>