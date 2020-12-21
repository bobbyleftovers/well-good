function InlineImage(el) {
	this.inlineImages = document.getElementsByClassName('js-inline-lazy-load')
  this.processImages()
}

InlineImage.prototype = {
  processImages: function() {
		Array.from(this.inlineImages).forEach(function (image) {
			image.src = image.dataset.src
			image.classList.add('inline-image-loaded')
		})
  }
}

module.exports = InlineImage
