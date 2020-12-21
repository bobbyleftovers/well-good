import { initVueApp } from 'lib/init-vue'
import { bus } from 'lib/appState'

module.exports = function (el) {
  initVueApp(el, {
    data () {
      return {
        flkty: null,
        format: el.dataset['format'] || 'large',
        size: parseInt(el.dataset['slideshowSize'])
      }
    },
    methods: {
      mountSlideshow () {
        this.flkty = new Flickity(this.$refs['slideshow__container'], { // eslint-disable-line
          cellAlign: 'center',
          percentPosition: false,
          prevNextButtons: (this.format === 'small' && this.size > 4),
          autoPlay: false,
          pageDots: false,
          draggable: true,
          wrapAround: (this.format === 'small' && this.size > 4),
          contain: true,
          initialIndex: 2,
          arrowShape: 'M49.961 100L4.79397e-06 50.0002L49.961 -5.44033e-07L56 6.0437L12.078 50.0001L56 93.9563L49.961 100Z',
          watchCSS: this.format === 'large',
          on: {
            staticClick (event, pointer, cellElement, cellIndex) {
              if (typeof cellIndex === 'number') {
                this.select(cellIndex)
              }
            },
            ready () {
              bus.$emit('trends-2021-slideshow:ready')
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
