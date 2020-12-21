(function ( $ ) {
  var StickyPin = require("@modules/main/sticky-pin/sticky-pin");

  module.exports = function ( el ) {
    // unveil images retina lazy loading
    var t = this,
      timer = 0,
      $window = $(window);

    $window.bind('load.unveil ajaxSuccess', function() {
      clearTimeout(timer);
      timer = setTimeout(function(){
        t.unveilImgs();
      }, 1000);
    });

    t.detectFeaturedImage = function(el) {
      var parentsLength = $(el).parents('.post__featured-image-wrapper').length;
      return parentsLength;
    }

    t.reInitStickyPin = function(el) {
      var isFeaturedImage = t.detectFeaturedImage(el);

      if( isFeaturedImage ) {
        var parent_id = $(el).parents('.post').attr('id'),
            $pins = $('*[data-sticky-id='+parent_id+']');

        $pins.each( function(index, el) {
          new StickyPin(el);
          $(el).attr('data-sticky-id', 'done');
        });

      }
    }

    t.unveilImgs = function() {
      $('.image-module-img').unveil(200, function() {
        $(this).load(function() {

          t.reInitStickyPin(this);

          $(this).closest('.image-module').css({
            'background-image': "url('"+$(this).attr('src')+"')",
            'opacity': 1
          });
        });

      });

    };

  };
})( jQuery );
