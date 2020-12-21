var $ = require('jquery');

var HomePage = function(el) {
  this.$el = $(el)
  this.body = document.querySelector('body')
  this.articles = document.querySelectorAll('article')
  this.activeArticle = null

  this.activity = {}
  this.pageUrl = window.location.href

  this.events()
}

HomePage.prototype = {

  /**
   * Check if module is visible on page
   * @param {object} element 
   * @return boolean moduleVisible
   */
  events: function() {
    for (let i = 0; i < this.articles.length; i++) {
      if (this.articles.length) {
        this.articles[i].addEventListener('mouseover', () => {
          if (this.activeArticle !== null) {
            this.articles[this.activeArticle].classList.remove('hovering')
          }

          this.articles[i].classList.add('hovering')
          this.activeArticle = i
        }, true)
      }
    }
  }
}

module.exports = HomePage;
