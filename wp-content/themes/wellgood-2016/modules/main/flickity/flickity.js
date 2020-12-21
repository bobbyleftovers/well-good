import Flickity from 'flickity'

function init( el, attr ) {
  var $el = $( el )
  if ( $el.initalized ) return;
  new Flickity( el, attr);
  $el.initalized = true;
}

module.exports =  el => {

  var $el = $( el ),
      delay               = 0,
      FLICKITY_BREAKPOINT = 642,
      breakpoint          = $el.data( 'breakpoint' ),
      attr;

  attr = {
    imagesLoaded: true,
    lazyLoad: true
  }

  if ( $el.data( "arrow" ) === "no" ) {
    attr.prevNextButtons = false;
  } else if ( $el.data( "arrow" ) === "long" ) {
    attr.arrowShape = "M24.5,49.8h52.9 M38.1,34.3L22.6,49.8l16,16";
  } else if ( $el.data( "arrow" ) === "summer" ) {
    attr.arrowShape = "M68.07,81.3l-33-30.39L67.83,20.74";
  } else {
    attr.arrowShape = "M29.2727273,0 L2,50 L29.2727273,100";
  }

  attr.pageDots = ($el.data( "dots" ) === "yes");
  attr.freeScroll = ($el.data( "free" ) === "yes");
  attr.contain = ($el.data( "contain" ) === "yes");
  attr.groupCells = ($el.data( "group" ) === "yes");

  if ( breakpoint === 0 ) {
    init( el, attr );
    return;
  }

  if ( !breakpoint ) {
    breakpoint = FLICKITY_BREAKPOINT;
  }

  // Controls Flickity display via content CSS rule
  // https://flickity.metafizzy.co/options.html#watchcss
  attr.watchCSS = true;

  $( window ).on( 'resize', function () {
    var window_width = $( window ).width()
    clearTimeout( delay );
    delay = setTimeout( function () {
      if ( !$el.hasClass('carousel--mobile') || window_width < breakpoint ) {
        init( el, attr );
        return;
      }
    }, 500 );
    if ( $el.initalized ) $el.flickity('resize');
  } );

  $( window ).trigger('resize');
  delay = setTimeout( function () {
    $( window ).trigger('resize');
  }, 1000 );

  document.addEventListener('DOMContentLoaded', function() {
    $( window ).trigger('resize');
  }, false);


};