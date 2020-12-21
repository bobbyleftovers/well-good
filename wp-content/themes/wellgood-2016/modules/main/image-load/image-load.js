var $ = require( 'jquery' );

window.wgLazyLoadImages = {};

module.exports = function(el) {
  var $el = $(el)
  if($el.hasClass('img-requested')) return;
  $el.addClass('img-requested')
  var src = $el.data('image-bg')

  var onload = function(){
    $el.css("background-image", "url("+src+")")
    $el.addClass('image-bg-loaded')
    $el.removeAttr('data-image-bg')
  }

  if(typeof window.wgLazyLoadImages[src] === 'undefined'){
    window.wgLazyLoadImages[src] = {
      'is_loaded': false,
      'callbacks': [onload]
    }
    var img = new Image();
    img.onload = function(){
      window.wgLazyLoadImages[src]['is_loaded'] = true
      window.wgLazyLoadImages[src]['callbacks'].forEach(function(callback){
        callback();
      })
    }
    setTimeout(function(){
      img.src = src
    },1)
  } else {
    window.wgLazyLoadImages[src]['callbacks'].push(onload)
    if(window.wgLazyLoadImages[src]['is_loaded']) onload();
  }
  
  
}