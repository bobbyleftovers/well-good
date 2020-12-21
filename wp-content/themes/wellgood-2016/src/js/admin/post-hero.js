class PostHero {
  constructor() {
    this.heroSettings = document.querySelector('#hero_settings')
    this.overwriteThumbnailSwitch = this.heroSettings.querySelector('#hero_settings_overwrite_thumbnail').nextSibling
    this.overwriteThumbnailDetails = this.heroSettings.querySelector('#hero_settings_overwrite_thumbnail_details')

    this.events()
  }

  events() {
    this.overwriteThumbnailSwitch.addEventListener('click', () => this.flipSwitch() )
  }

  flipSwitch() {
    if (this.overwriteThumbnailSwitch.classList.contains('-on')) {
      this.overwriteThumbnailSwitch.classList.remove('-on')
      this.overwriteThumbnailDetails.innerHTML = 'The video’s thumbnail will be fetched from API'
    } else {
      this.overwriteThumbnailSwitch.classList.add('-on')
      this.overwriteThumbnailDetails.innerHTML = 'The video’s thumbnail will be overridden'
    }
  }
}

jQuery(function() {
  new PostHero()
})