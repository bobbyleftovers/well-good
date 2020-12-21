var $ = require('jquery')

function EditorialtagModuleHalf(el) {
	this.delay = 0
	this.backgrounds = document.getElementsByClassName('editorialtag-module-half__background')
	this.$slides = $('.editorialtag-module-half__slides')
	this.$prev = $('.editorialtag-module-half__header--left')
	this.$next = $('.editorialtag-module-half__header--right')
	this.events()
}

EditorialtagModuleHalf.prototype = {
	events: function() {
		var $carousel = this.$slides.flickity({
			cellAlign: 'left',
			draggable: '>1',
			groupCells: false,
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

module.exports = EditorialtagModuleHalf;
