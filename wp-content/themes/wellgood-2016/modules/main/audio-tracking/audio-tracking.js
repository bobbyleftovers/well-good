/**
 * Initializes the audio tracking.
 * @constructor
 * @param {Object} el - The site footer element.
 */
function TrackAudio(el) {
  this.el = el;
  var audioElems = this.el.querySelectorAll('audio')

  for (var i = 0; i < audioElems.length; i++) {
    audioElems[i].setAttribute('data-played', 'false')
    audioElems[i].setAttribute('data-order',i)

    new AudioPlayer(audioElems[i])
  }
}

function AudioPlayer(audio) {
  this.audio            = audio
  this.file             = (this.audio.querySelector('source').src.split('/').pop()).split('?')[0]

  this.durationTracker  = { 25: false, 50: false, 75: false, 100: false }
  this.logger           = new AudioLogger()

  this.events()
}
AudioPlayer.prototype = {
  onLoadedData: function() {
    this.totalTime = this.audio.duration
  },
  onPlay: function() {
    this.logger.logStart(this.audio.currentTime);

    var playStatus    = this.audio.getAttribute('data-played'),
        audioNumber   = parseInt(this.audio.getAttribute('data-order')) + 1,
        activeArticle = document.querySelector('article[data-url*="' + window.location.href + '"]'),
        articleTitle  = activeArticle.querySelector('h1.post__title').textContent;

    this.logger.log('audio play', { 'content': this.file } );

    if (playStatus == 'false' && typeof ga != 'undefined') {
      this.logger.log(
        'AudioPlaybackInitiated',
        {
          'articleTitle': articleTitle,
          'audioInstance': audioNumber
        }
      );
      this.audio.setAttribute('data-played', 'true')
    }
  },
  onPause: function() {
    this.logger.logStop(this.audio.currentTime);
    this.logger.log('audio stop', { 'content': this.file } );
  },
  onTimeUpdate: function() {
    var update            = this,
        percentage        = (this.audio.currentTime / this.totalTime) * 100;

    Object.keys(this.durationTracker).forEach(function(p) {
      if (percentage >= p && update.durationTracker[p] === false) {
        update.durationTracker[p] = true;
        update.logger.log(
          'audio progress',
          { 'progress': p + '%', 'content': update.file }
        )
      }
    })
  },
  onEnded: function() {
    if (this.durationTracker[100] === false) {
      this.durationTracker[100] = true;
      this.logger.log(
        'audio progress',
        { 'progress': '100%','content': this.file }
      )
    }
  },
  events: function() {
    var event = this

    this.audio.onloadeddata  = function() { event.onLoadedData() }
    this.audio.onplay        = function() { event.onPlay() }
    this.audio.onpause       = function() { event.onPause() }
    this.audio.ontimeupdate  = function() { event.onTimeUpdate() }
    this.audio.onended       = function() { event.onEnded() }
  }
}

function AudioLogger() {
  this.times = [];
  this.currentTime = {}
}
AudioLogger.prototype = {
  log: function(event, params) {
    if (!dataLayer) { return }

    var props = Object.assign({}, { 'event': event }, (params || {}));
    dataLayer.push(props)

  },
  logStart: function(time) {
    this.currentTime.start = time
  },
  logStop: function(time) {
    this.currentTime.end = time;
    this.times.push(this.currentTime);

    // Reset
    this.currentTime = {}
  }
}

module.exports = TrackAudio;
