import Vue from 'vue';

require('./polyfills');
require('./related-content.vue.js');

module.exports = function(el) {
  /* mount the vue app */
  new Vue({
    el: el,
    data: function() {
      return {
        isLoaded: false
      };
    },
    methods: {
      onPostsLoaded: function(posts) {
        if (posts.length > 0) {
          this.isLoaded = true;
        } else {
          this.$el.remove();
        }
      }
    }
  });
};
