var parselyCallbacks = require('parselyCallbacks');
var debounce = require('@modules/main/debounce/debounce');
var $ = require('jquery');

function inView(viewportHeight, el) {
    var rect = el.getBoundingClientRect();
    return ((rect.top <= 0 && rect.bottom > 80) || (rect.top > 0 && rect.top <= viewportHeight / 2));
}

function Parsely( el ) {
    this.$el = $(el);
    this.$scrolls = this.$el.find('[data-parsely-scroll]');

    parselyCallbacks.onload(this.init.bind(this));
};

Parsely.prototype.init = function(parsely) {
    // var $window = $(window);
    // $window.on('scroll', debounce(this.scroll.bind(this, parsely)));
    // $window.on('load', this.listenVideo.bind(this, parsely));
}

Parsely.prototype.scroll = function(parsely) {
    this.$scrolls = this.$scrolls.filter(function(i, el) {
        if (inView(window.innerHeight, el)) {
            var data = {
                url: location.href,
                urlref: document.referrer,
                js: 1
            };
            parsely.beacon.trackPageView(data);
            return false;
        }
        return true;
    });
};

Parsely.prototype.listenVideo = function(parsely) {
    this.$el.find('[data-parsely-video].video-js').each(function(i, video) {
        var id = video.id;
        var player = window.videojs(id);

        player.on('play', function() {
            var data = {
                duration: player.mediainfo.duration,
                image_url: player.mediainfo.poster,
                pub_date_tmsp: Date.parse(player.mediainfo.publishedAt),
                title: player.mediainfo.name,
                author: "",
                tags: player.mediainfo.tags,
                video_platform: "brightcove"
            };

            parsely.video.trackPlay(player.mediainfo.id, data, window.location.href);
        });
    });
}

module.exports = Parsely;
