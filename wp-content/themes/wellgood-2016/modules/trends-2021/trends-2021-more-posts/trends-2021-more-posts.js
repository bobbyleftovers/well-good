module.exports = function(el) {
  setTimeout(() => {
    const length = parseInt(el.dataset.postsLength)
    var initialIndex = 1
    if(length <= 2) initialIndex = 0
    this.flickity = new Flickity(el, {
      cellAlign: 'center',
      percentPosition: true,
      prevNextButtons: false,
      autoPlay: false,
      pageDots: true,
      draggable: true,
      wrapAround: false,
      initialIndex,
      groupCells: false,
      freeScroll: true,
      contain: true
    })
  }, 0)
}