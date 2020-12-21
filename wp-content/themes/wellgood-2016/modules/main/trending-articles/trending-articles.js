import Vue from 'vue'
import axios from 'axios'

module.exports = function(el) {

  Vue.component('trending-articles', {
    props: ['fetch_parsely'],
    data: function() {
      return {
        posts: []
      };
    },
    methods: {
      truncate: function(str, count, append) {
        if (!append) append = '...';
        if (!count) count = 12;
        var truncated = str
          .split(' ')
          .splice(0, count)
          .join(' ');
        if (truncated.length < str.length) truncated += append;
        return truncated;
      },
      fetchPosts: function() {
        var self = this;
        var max = 4;

        //is parsely?
        if(this.fetch_parsely){
          var endpoint = 'parsely'
        } else {
          endpoint = 'wp'
        }

        //make request
        axios.get(`/wp-json/wellandgood/v1/trending_articles/${endpoint}?max=${max}`).then(function(response){
          for (const i in response.data) {
            response.data[i].excerpt = self.truncate(response.data[i].excerpt, 14, '...')
          }
          self.posts = response.data
        })
      }
    },
    mounted: function() {
      this.fetchPosts();
    }
  });

  new Vue({el})
};
