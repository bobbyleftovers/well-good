(function($) {
  var debounce = require( "@modules/main/debounce/debounce" );

  function AnchorLink( el ) {
    var t = this;
    t.wait = 0;
    t.$el = $( el );

    t.$offset = $( this.$el.data( "anchor-link-offset" ) );

    this.$el.on( "click", 'a[href*="#"]:not([href="#"]):not(.avoid-anchor-link)', () => {
      if (window.location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
        || window.location.hostname == this.hostname) {

        var target = $(this.hash);
        var target_name = this.hash.slice(1);
        target = target.length ? target : $('[name=' + target_name +']');
        function scrollToAnchor() {
          console.log(t.$offset)
          var target_offset = this.$el.$offset.height();
          target_offset += target_name == "comments" ? 40 : 0;
          $('html,body').animate({
            scrollTop: target.offset().top - target_offset
          }, 250);
        }
        if (target.length) {
          clearTimeout(t.wait);
          t.wait = setTimeout(function(){
            scrollToAnchor();
          }, 200);
          scrollToAnchor();
          if(history.pushState) {
            history.pushState(null, null, this.hash);
          }
          else {
            location.hash = this.hash;
          }
          return false;
        }
      }
    });
  }

  module.exports = AnchorLink;
})(jQuery);
