(function($) {

  function SummerHeader() {

    clearTimeout(delay);

    var delay = setTimeout(function () {
      if (document.querySelector('.summer-header__image--background img')) {
        var backgroundLoaded = document.querySelector('.summer-header__image--background img').complete;

        if ( backgroundLoaded ) {
          $('.summer-header__images').removeClass('waypoint');
        }
      }
    }, 200);

    // Smooth scroll to content
    // https://css-tricks.com/snippets/jquery/smooth-scrolling/
    $('a.js-scroll-to[href*="#"]').on('click', function (event) {
      event.stopImmediatePropagation()
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
          var offset = window.innerWidth <= 767 ? 30 : 60
          $('html,body').animate({
            scrollTop: target.offset().top - offset
          }, 500);
        }
      }
    });
  }

  module.exports = SummerHeader

})(jQuery);

