import { initVueApp } from 'lib/init-vue'
import 'flickity'

module.exports = function (el) {
  initVueApp(el, {
    data () {
      return {
        flkty: null,
        size: el.dataset['size'],
        limit: 1
      }
    },
    methods: {
      mountSlideshow () {
        this.limit = 1
        if (window.innerWidth >= 768) {
          this.limit = 2
        }
        if (window.innerWidth >= 1024) {
          this.limit = 3
        }

        this.flkty = new Flickity(this.$refs['flkty'], { // eslint-disable-line
          cellAlign: 'left',
          prevNextButtons: this.size > this.limit,
          autoPlay: false,
          pageDots: false,
          draggable: true,
          imagesLoaded: true,
          wrapAround: (this.size > this.limit),
          contain: (this.size <= this.limit),
          percentPosition: false,
          arrowShape: 'M5.74358e-07 49.5L86 -1.80705e-06L86 99L5.74358e-07 49.5Z',
          on: {
            ready: () => {
              window.addEventListener('resize', (e) => {
                let limit = 1
                if (window.innerWidth >= 768) {
                  limit = 2
                }
                if (window.innerWidth >= 1024) {
                  limit = 3
                }

                if (this.limit !== limit) {
                  this.flkty.destroy()
                  this.mountSlideshow()
                }
              })
            }
          }
        })
      }
    },
    mounted () {
      this.mountSlideshow()
    }
  })
}
