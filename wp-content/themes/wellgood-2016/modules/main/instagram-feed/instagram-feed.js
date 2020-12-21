import Vue from 'vue';
var axios = require('axios');

module.exports = function(el) {
  /* mount the vue app */
  new Vue({
    el: el,
    data: function() {
      return {
        posts: []
      };
    },
    mounted(){
      var self = this;
      axios.get('/wp-json/wellandgood/v1/instagram/feed').then(function(response){
        self.posts = response.data.slice(0, 6)
      })
    }
  });
};
