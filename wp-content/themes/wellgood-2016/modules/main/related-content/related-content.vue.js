import Vue from 'vue';
var axios = require('axios');
var getLocation = require('./get-location');
var getFileName = require('./get-filename');

// register global component <related-content>
Vue.component('related-content', {
  props: ['url', 'secret', 'apikey', 'limit', 'parent-article-permalink', 'startDate', 'currentTitle', 'currentImage'],
  data: function() {
    return {
      posts: [],
      originalLimit: null,
      fetchTries: 0,
      fetchedFallback: false,
      maximumFetchTries: 3
    };
  },
  computed: {
    computedLimit: function() {
      // +1 because we want to eliminate any reference to current post
      // *3 because we are dealing with duplicated sites: wellandgood.com, www.wellandgood.com, test.wellandgood.com
      return (parseInt(this.limit) + 1) // * 3;
    },
    publistStartDate () {
      const d = new Date()
      d.setMonth(d.getMonth() - 6)

      let month = '' + (d.getMonth() + 1)
      let day = '' + d.getDate()
      let year = d.getFullYear()

      if (month.length < 2) {
        month = '0' + month
      }

      if (day.length < 2) {
        day = '0' + day
      }

      return [year, month, day].join('-')
    }
  },
  watch: {
    posts () {
      if (this.posts.length) {
        this.trackPostsGA()
      }
    }
  },
  methods: {
    trackLinkGA ($event, index) {
      if (dataLayer) {
        dataLayer.push({
          'event': 'related_content',
          'info': index
        })
      }
      window.location = $event.currentTarget.getAttribute('href')
    },
    trackPostsGA () {
      if (dataLayer) {
        dataLayer.push({
          'event': 'related_content_impression',
          'urls': this.posts.map((p) => p.url).slice(0, 2)
        })
      }
    },
   parsely: function(endpoint, params) {
     if(endpoint === '/related'){
      var url = '/wp-json/wellandgood/v1/parsely/related?'
     } else {
      url =
      'https://api.parsely.com/v2' +
      endpoint +
      '?secret=' +
      this.secret +
      '&apikey=' +
      this.apikey + '&';
     }
      for (var key in params) {
        if (params.hasOwnProperty(key)) {
          url +=  key + '=' + params[key] + '&';
        }
      }
      return axios.get(url);
    },
    truncate: function(str, count, append) {
      if (!append) append = '...';
      if (!count) count = 18;
      var truncated = str
        .split(' ')
        .splice(0, count)
        .join(' ');
      if (truncated.length < str.length) truncated += append;
      return truncated;
    },
    fetchPosts: function(url = this.url) {
      var self = this;
      this.fetchTries++;
      var params = {
        limit: this.computedLimit,
        url: url,
        pub_date_start: this.startDate
      };
      return this.parsely('/related', params).then(function(response) {
        self.posts = self.filterPosts(response.data);
        if (!self.fetchedFallback && self.posts.length === 0 && self.fallbackUrl) {
          self.fetchedFallback = true
          return self.fetchPosts(self.fallbackUrl)
        }
        
        if (
          self.posts.length > 0 ||
          self.fetchTries >= self.maximumFetchTries
        ) {
          self.$emit('posts-loaded', self.posts);
        }
      });
    },
    filterPosts: function(posts) {
      var postsUrls = []; //avoid same url posts
      var postsTitles = []; // avoid same title posts (url could be different)
      var parentArticleLocation = getLocation(this.parentArticlePermalink);
      window.RELATED_POSTS.push(parentArticleLocation.pathname);
      var posts = posts.filter((post) => {
        var l = getLocation(post.url);
        if (
          l.hostname != 'www.wellandgood.com' &&
          l.hostname != 'wellandgood.com'
        )
          return false;
        post.url = 'https://www.wellandgood.com' + l.pathname;
        post.pathname = l.pathname;
        if (
          postsTitles.indexOf(post.title) == -1 &&
          postsUrls.indexOf(post.url) == -1 &&
          window.RELATED_POSTS.indexOf(post.pathname) == -1 &&
          post.url != this.url &&
          this.currentImage != getFileName(post.image_url) &&
          l.pathname != window.location.pathname
        ) {
          postsUrls.push(post.url);
          postsTitles.push(post.title);
          return true;
        }
        return false;
      });
      posts = posts.slice(0, parseInt(this.originalLimit));

      window.RELATED_POSTS = window.RELATED_POSTS.concat(
        posts.reduce(function(newArr, curr) {
          newArr.push(curr.pathname);
          return newArr;
        }, [])
      );

      if (
        posts.length < this.originalLimit &&
        this.fetchTries < this.maximumFetchTries
      ) {
        this.limit =
          parseInt(this.limit) + (this.originalLimit - posts.length);
        this.fetchPosts();
      }
      return posts;
    }
  },
  mounted: function() {
    if (typeof window.RELATED_POSTS == 'undefined') {
      window.RELATED_POSTS = [];
    }
    this.originalLimit = this.limit;
    this.fetchPosts();
  }
});
