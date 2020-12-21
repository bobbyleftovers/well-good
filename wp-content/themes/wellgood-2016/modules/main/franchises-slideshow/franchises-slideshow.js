import Flickity from 'flickity'

module.exports = function(el) {
  new Flickity( el, {
    cellAlign: 'left',
    contain: true,
    freeScroll: true,
    pageDots: false,
    arrowShape : "M24.5,49.8h52.9 M38.1,34.3L22.6,49.8l16,16"
  });
};
