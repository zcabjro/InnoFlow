<template>             
<div class="comment">


  <div class="col-lg-12">
    <h1>Commits</h1>              
    <div v-for="i in commits">
      {{i.id}}: {{i.comment}} <br>           
      by {{i.commiter.username}} ({{i.date}}) <br><br>
    </div>            
  </div>

    <if-message ref="message"></if-message>
  <div >
    <h1>Comments</h1>              
            <div v-for="i in comments" style=".if-card { width: 100%; height: 50%;  
              /* These are technically the same, but use both */
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;}" >
              <if-card> 
                <span class="h3">{{i.text}}</span>                            
                <br>by {{i.owner.username}} ({{i.date}})
              </if-card>
            </div> 

              <if-card>       
                <b>Message</b>
                <br>
                <textarea v-model="newComment" style="box-sizing:border-box" placeholder="20+ chars"></textarea>
                <br>
     
                  <a  href="#" v-on:click="submitComment($event)">Submit Comment</a>  
              </if-card>         

  </div>


</div> 
</template>

<script>
  import VueMarkdown from 'vue-markdown' // Markdown display component

  import IfCard from './IfCard.vue'
  import IfMessage from './IfMessage.vue'
  // Helper for setting default IfInnovations data
  function defaultCommentsData() {
    return {

      // Whether innovations have been syntax highlighted or not
      highlighted: false,
      newComment: '',
      commits: [],

      // Innovation markup snippets
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
    
    // Components used by IfInnovations
    components: {
      VueMarkdown,
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
      console.log(to);  
      next(commentsComponent => {
        commentsComponent.init(to.params.id, to.params.codeReviewID);
      }); 
    },

/*    beforeRouteUpdate (to, from, next) {
      if (typeof this != 'undefined'){
        if (to.params.id !== this.projectID) {
          this.init(to.params.id, to.params.codeReviewID);
        }
      }
      next();
    },
*/
    created() { 
      this.loadCommitsAndComments();
    },

    computed: {
      validCodeReviewFields() { 
        if (!this.newComment || this.newComment.length < 20) {
          this.$refs.message.display('Comment must be at least 20 characters.');
          return false;
        } 
        return true;
      }
    },
    methods: {
      init(projectID, codeReviewID) {
        console.log("running init");
        this.projectID = projectID;
        this.codeReviewID = codeReviewID;
        this.loadDetails(); 
      },
      // Request innovations

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
        else { //redirect to projects page
//          this.resetData(); 
        }
      },


      loadCommitsAndComments() { 
//          axios.get('http://innoflow.app/api/projects/07fa8c3e-546d-4e7a-8edb-4274a9e631c3/codereviews/1/comments')
        axios.get('api/projects/' + this.projectID +'/codereviews/' + this.codeReviewID)
          .then((res) => {
//            this.comments = res.data.comments;
console.log(res.data);
              this.comments = res.data.comments;
              this.commits = res.data.commits;
console.log("comments and commits has been set");

console.log(this.commits);
console.log(this.comments);

          })
          .catch((error) => {
            console.log(error);
            console.log('Failed to load code review');
          });
      },
 


      submitComment(e) {
        e.preventDefault();
        if (this.validCodeReviewFields) { 
          console.log("projectID:"+this.projectID)
          console.log("commitID:"+this.codeReviewID)
          axios.post('api/projects/' + this.projectID + '/codereviews/' + this.codeReviewID + '/comments', { projectID: this.projectID, codeReviewId: this.codeReviewID, message: this.newComment })
            .then((res) => {
              console.log('Succeeded in submitting comment.');
            })
            .catch((error) => {
              console.log(error);
              console.log('Failed to submit comment.');
            });
 
        }        
      }

    }
  }
</script>

<style scoped> 
</style>