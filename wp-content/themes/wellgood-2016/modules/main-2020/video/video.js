import select  from 'select-dom'
import { bus } from 'lib/appState'

function loadYTScript(){

  if(typeof window.YT != 'undefined') return;

  // 2. This code loads the IFrame Player API code asynchronously.
  var tag = document.createElement('script');

  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function showCover($cover, show = true){
  if(show){
    $cover.classList.remove('_hidden');
  } else {
    $cover.classList.add('_hidden');
  }
}

function initPlayer($el){

  const $iframe = select('.video__iframe', $el)
  const $cover = select('.video__cover', $el)

  var player = new window.YT.Player($iframe, {
  videoId: $iframe.getAttribute('data-id'),
  events: {
      'onStateChange': function(event){
        if(event.data === 0) showCover($cover, true);
      }
    }});

  $cover.onclick = () => {
    player.playVideo();
    showCover($cover, false);
  }
}

module.exports = function(el){

  if(typeof window.YT !== 'undefined') initPlayer(el)
  else bus.$on('onYouTubeIframeAPIReady', () => initPlayer(el))

  loadYTScript()
}
