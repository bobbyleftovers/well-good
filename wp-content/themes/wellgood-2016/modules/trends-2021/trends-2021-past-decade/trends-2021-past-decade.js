module.exports = function (el) {
  new Flickity(el, { // eslint-disable-line
    cellAlign: 'left',
    prevNextButtons: true,
    freeScroll: true,
    autoPlay: false,
    pageDots: true,
    draggable: true,
    wrapAround: false,
    contain: true,
    initialIndex: 0,
    groupCells: true,
    arrowShape: 'M49.961 100L4.79397e-06 50.0002L49.961 -5.44033e-07L56 6.0437L12.078 50.0001L56 93.9563L49.961 100Z'
  })
}