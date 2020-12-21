import animateScrollTo from 'animated-scroll-to'

function AllExperts(el) {
  this.expert = new URLSearchParams(window.location.search).get('expert')
  this.scrollOptions = {
    speed: 2000,
    verticalOffset: -95
  }

  this.slides = document.querySelector('.all-experts__recent-posts')
	
	this.events()
}

AllExperts.prototype = {
	events: function() {
    this.setUpCarousel()

    if (this.expert) {
      this.scrollToExpert()
    }
  },  
  
  scrollToExpert: function() {
    const target = document.querySelector(`#${this.expert}`)

    animateScrollTo(target, this.scrollOptions)
  },

  setUpCarousel: function() {
    let carousel = new Flickity( this.slides, {
      cellAlign: 'left',
      groupCells: true,
      contain: true,
      pageDots: true,
      wrapAround: true,
      draggable: '>1',
      watchCSS: true,
			groupCells: 1,
			prevNextButtons: false,
      // setGallerySize: false,
      resize: false
    })
		setTimeout(function() {
			carousel.resize()
		}, 1000)
  }
}

module.exports = AllExperts

