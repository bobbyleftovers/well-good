var $ = require('jquery')

function EditorialtagModuleFull(el) {
	this.backgrounds = document.getElementsByClassName('editorialtag-module-full__background')
	this.$slides = $('.editorialtag-module-full__slides')
	this.$prev = $('.editorialtag-module-full__header--left')
	this.$next = $('.editorialtag-module-full__header--right')
	this.events()
}

EditorialtagModuleFull.prototype = {
	events: function() {
		var self = this
		var $carousel = this.$slides.flickity({
			cellAlign: 'left',
			draggable: '>1',
			groupCells: false,
			on: {
				change: function( index ) {
					for (var i = 0; i < self.backgrounds.length; i++) { 
						if (i != index) {
							self.backgrounds[i].classList.remove('editorialtag-module-full__background--active')
						}
					}
					self.backgrounds[index].classList.add('editorialtag-module-full__background--active')
				}
			},
			pageDots: false,
			prevNextButtons: false,
			setGallerySize: false,
			wrapAround: true
		})
		this.$prev.on( 'click', function() {
			$carousel.flickity('previous')
		})
		this.$next.on( 'click', function() {
			$carousel.flickity('next')
		})
		setTimeout(function() {
			$carousel.flickity('resize')
		}, 1000)
	},
}

module.exports = EditorialtagModuleFull

