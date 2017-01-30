<template>
    <div class="loader">Loading...</div>
</template>

<script>
  export default {
    name: 'loading',
    data() {
      return {
        loadTimeout: null,
        maxPolls: 10,
        polls: 0
      }
    },
    mounted: function() {
      this.startPolling();
    },
    methods: {            
      pollToken() {
        console.log('GET api/token');
        this.$http
            .get('/api/token')
            .then(this.authorised, this.error);
      },
      authorised(res) {
        console.log(res.body['authorized']);
        if (res.body['authorized']) {
          this.stopPolling();
          this.$router.go('/dashboard');
        }
        else if (this.polls++ == this.maxPolls) {
          this.stopPolling('Max polls reached');
        }
        else {
          console.log("polls: " + this.polls + ", max: " + this.maxPolls);
        }
      },
      error(res) {
        this.stopPolling('Error response');
      },
      startPolling() {
        if (this.loadTimeout === null) {
          this.loadTimeout = setInterval(this.pollToken, 1000);
        }
      },
      stopPolling(msg) {
        clearInterval(this.loadTimeout);
        this.loadTimeout = null;

        if (msg) {
          console.log(msg);
        }
      }
    }
  }
</script>

<style>
    .loader,
    .loader:before,
    .loader:after {
        background: #aaa;
        -webkit-animation: load1 1s infinite ease-in-out;
        animation: load1 1s infinite ease-in-out;
        width: 1em;
        height: 4em;
    }
    .loader {
        color: #aaa;
        text-indent: -9999em;
        margin: 88px auto;
        position: relative;
        font-size: 11px;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }
    .loader:before,
    .loader:after {
        position: absolute;
        top: 0;
        content: '';
    }
    .loader:before {
        left: -1.5em;
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }
    .loader:after {
        left: 1.5em;
    }
    @-webkit-keyframes load1 {
        0%,
        80%,
        100% {
            box-shadow: 0 0;
            height: 4em;
        }
        40% {
            box-shadow: 0 -2em;
            height: 5em;
        }
    }
    @keyframes load1 {
        0%,
        80%,
        100% {
            box-shadow: 0 0;
            height: 4em;
        }
        40% {
            box-shadow: 0 -2em;
            height: 5em;
        }
    }
</style>