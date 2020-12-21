var url = require('url')

function PostHero(el) {
  this.container = el.querySelector('.post-hero__video-container')
  this.iframe = el.querySelector('.post-hero__iframe')
  this.playButton = el.querySelector('.post-hero__video-play-button')

  this.url = url.parse(this.iframe.src, true)

  this.events()
  this.loaded()
}

PostHero.prototype = {
  events: function() {
    this.playButton.addEventListener('click', this.playVideo.bind(this))
  },

  playVideo: function(e) {
    e.preventDefault()
    this.url.query['autoplay'] = '1'
    delete this.url.search
    this.iframe.src = url.format(this.url)
    this.iframe.style = 'z-index: 1'
    this.container.classList.add('post-hero__video-container--play')
  },

  loaded: function() {
    this.iframe.classList.add('post-hero__iframe--loaded')
  }
}


module.exports = PostHero
