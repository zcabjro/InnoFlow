<template>            
  <div class="comment">



    <if-message ref="message"></if-message>
<div>
            <div v-for="i in comments">
              <if-card> 
                <span class="h3">{{i.text}}</span>                            
                <br>by {{i.owner.username}} ({{i.date}})
              </if-card>
            </div>
          </div>

    <div>
              <if-card>       
                <b>Message</b><br><textarea v-model="newComment" style="width: 100%; height: 50%;" placeholder="20+ chars"></textarea><br>
     
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
      console.log(to.params.id+"i like sandwiches");
      console.log(to.params.codeReviewID+"i like sandwiches2");
      console.log(next);
      next(commentsComponent => {
        commentsComponent.init(to.params.id, to.params.codeReviewID);
      });
      console.log(next);
      console.log("executed next?");
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
      this.loadComments();
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
              this.loadComments();  

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


      loadComments() { 
//          axios.get('http://innoflow.app/api/projects/07fa8c3e-546d-4e7a-8edb-4274a9e631c3/codereviews/1/comments')
        axios.get('api/projects/' + this.projectID +'/codereviews/' + this.codeReviewID + '/comments')
          .then((res) => {
//            this.comments = res.data.comments;
console.log("comments has been set");
console.log(res.data);
              this.comments = res.data;

console.log(this.comments);

          })
          .catch((error) => {
            console.log(error);
            console.log('Failed to load comments');
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