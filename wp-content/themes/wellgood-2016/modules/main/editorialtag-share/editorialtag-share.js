var $ = require('jquery')

function EditorialtagShare(el) {
	this.shareModal = document.querySelectorAll('.editorialtag-share')[0]
	this.shareButton = document.querySelectorAll('.editorialtag-grid-card__content--share')
	this.closeButton = document.querySelectorAll('.editorialtag-share__header--close')[0]
	this.title = document.querySelectorAll('.editorialtag-share__article--title')[0]
	this.image = document.querySelectorAll('.editorialtag-share__article--image')[0]
	this.link = document.querySelectorAll('.editorialtag-share__article--link')[0]
	this.shareFacebook = document.querySelectorAll('.editorialtag-share__link--facebook')[0]
	this.shareTwitter = document.querySelectorAll('.editorialtag-share__link--twitter')[0]
	this.sharePinterest = document.querySelectorAll('.editorialtag-share__link--pinterest')[0]
	this.shareCopy = document.querySelectorAll('.editorialtag-share__link--copy')[0]
	this.shareOpen = false
	
	this.events()
}

EditorialtagShare.prototype = {
	events: function() { 
		var self = this
		self.shareButton.forEach(function(button) {
			button.addEventListener("click", function(e) {
				e.preventDefault()
				var item = $(this).closest('.editorialtag-grid-card')[0]
	
				self.openShare(item);
			})
		})
		self.closeButton.addEventListener('click', $.proxy( self.closeShare, self ))

		document.addEventListener('keyup', function(e) {
			if (!self.shareOpen) return;
			if (e.keyCode === 27) self.closeShare();
		});
	},
	openShare: function(item) {
		var title = item.querySelectorAll('.editorialtag-grid-card__content--title')[0].innerHTML
		var image = item.querySelectorAll('.image-module-img')[0].getAttribute('data-src')
		var link = item.querySelectorAll('.editorialtag-grid-card__content a')[0].getAttribute('href')
		var shareFacebook = item.querySelectorAll('.editorialtag-grid-card__share--facebook')[0].getAttribute('href')
		var shareTwitter = item.querySelectorAll('.editorialtag-grid-card__share--twitter')[0].getAttribute('href')
		var sharePinterest = item.querySelectorAll('.editorialtag-grid-card__share--pinterest')[0].getAttribute('href')

		this.title.innerHTML = title
		this.image.style = 'background-image:url(' + image + ')'
		this.link.href = link
		this.shareFacebook.href = shareFacebook
		this.shareTwitter.href = shareTwitter
		this.sharePinterest.href = sharePinterest
		this.shareCopy.href = link

		this.shareModal.classList.add('editorialtag-share--open')
		this.shareOpen = true
	},
	closeShare: function() {
		var self = this
		self.shareModal.classList.remove('editorialtag-share--open')

		setTimeout(function() {
			self.shareOpen = false
			self.title.innerHTML = ''
			self.image.style = ''
			this.link.href = ''
			this.shareFacebook.href = ''
			this.shareTwitter.href = ''
			this.sharePinterest.href = ''
			this.shareCopy.href = ''
		}, 500)
	}
}

module.exports = EditorialtagShare
