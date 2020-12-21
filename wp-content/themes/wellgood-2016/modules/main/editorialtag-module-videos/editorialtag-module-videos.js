var $ = require('jquery')

function EditorialtagModuleVideos(el) {
	this.$slides = $('.editorialtag-module-videos__slides')
	this.events()
}

EditorialtagModuleVideos.prototype = {
	events: function() {
		var $carousel = this.$slides.flickity({
			cellAlign: 'left',
			draggable: '>1',
			groupCells: 1,
			pageDots: true,
			prevNextButtons: false,
			setGallerySize: false,
			wrapAround: true
		})
		setTimeout(function() {
			$carousel.flickity('resize')
		}, 1000)
	},
}

module.exports = EditorialtagModuleVideos;

