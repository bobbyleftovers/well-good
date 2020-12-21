(function ( $ ) {
  function AjaxLoadMore( el ) {
    var t = this,
      resultSelector,
      buttonSelector;

    t.$el = $( el );
    t.loading = false;
    t.$window   = $( window );

    resultSelector = t.$el.data( "ajax-selector" );
    var resultChildSelector = t.$el.data( "ajax-child-selector" );
    buttonSelector = t.$el.data( "ajax-button" );

    t.$el.on( "click", buttonSelector, function ( e ) {
      var $button = $( this ),
          url     = $( this ).attr( "href" ) + " " + resultSelector,
          $temp   = $( "<div></div>" );

      $button.addClass('disabled').text('Loading...');

      e.preventDefault();

      // prevent multiple clicks
      if ( t.loading ) {
        return;
      }

      t.loading = true;

      $temp.load( url, function () {
        if(resultChildSelector) {
          $temp.find(resultChildSelector).hide().appendTo( t.$el.find(resultChildSelector).parent() ).slideDown( 500, function() { t.$window.resize(); } );
          $temp.find(buttonSelector).insertBefore( $button );
        } else {
          $temp.children().hide().insertBefore( $button ).slideDown( 500, function() { t.$window.resize(); } );
        }
        $button.remove();
        t.loading = false;
        t.$window.resize();
      } );
    } );
  }

  module.exports = AjaxLoadMore;
})( jQuery );
