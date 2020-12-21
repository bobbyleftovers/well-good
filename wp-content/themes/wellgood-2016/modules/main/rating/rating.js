(function ( $ ) {

  /**
   * Set a cookie to handle content rating.
   * @constructor
   * @param {Object} el - The element containing the rating.
   */

  function Rating( el ) {
    var self = this;

    self.$el = $(el);
    self.$stars = self.$el.find('.rating__star');
    self.$notice = self.$el.siblings('.js-rating-notice');

    self.$stars.each( function(key, elem) {
      var value = key + 1;
      var id = self.$el.attr('data-post-id');

      $(elem).on( 'click', function() {
        self.saveRating(value, id);
      });

    });

  }

  Rating.prototype.saveRating =  function( value, id ) {
    var self = this;
    self.$el.attr('data-rating', value);

    $.ajax({
      url: '/wp-json/wellandgood/v1/'+id+'/rating',
      type: 'POST',
      dataType: 'json',
      data: {
        vote: value,
        id: id
      },
      beforeSend: function ( xhr ){
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        self.$notice.text('Saving rating...');
      }
    }).done( function(data) {
      self.$notice.text('Saved!');

    }).error( function(error) {
      console.log(error);
    });
  }

  module.exports = Rating;

})(jQuery);
