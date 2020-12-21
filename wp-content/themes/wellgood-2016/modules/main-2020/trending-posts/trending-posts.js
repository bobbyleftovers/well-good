import { registerVueComponent } from 'lib/init-vue'
import axios from 'axios'

module.exports = function(el) {
  registerVueComponent(el, {
    props: ['is_parsely', 'total', 'category', 'section', 'tag'],
    data: function() {
      return {
        posts: [],
        max: 2
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

        //is parsely?
        if(this.is_parsely){
          var endpoint = 'parsely'
        } else {
          endpoint = 'wp'
        }

        var query = `?max=${this.max}`;

        if(this.category && this.category != 0) {
          query += `&category=${this.category}`
        }

        if(this.section && this.section != 0) {
          query += `&section=${this.section}`
        }

        if(this.tag && this.tag != 0) {
          query += `&tag=${this.tag}`
        }

        //make request
        axios.get(`/wp-json/wellandgood/v1/trending_articles/${endpoint}${query}`).then(function(response){
          for (const i in response.data) {
            response.data[i].excerpt = self.truncate(response.data[i].excerpt, 14, '...')
          }
          self.posts = response.data
        })
      }
    },
    mounted: function() {
      this.max = parseInt(this.total)
      this.fetchPosts();
    }
  });
};
