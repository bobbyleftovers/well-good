import Vue from 'vue'
import Flickity from 'flickity'
module.exports = function(el) {
  let totalItems = parseInt(el.getAttribute('totalitems'))
  if (isNaN(totalItems)) {
    totalItems = 1
  }
  new Vue({
    el: el,
    data: {
      totalItems,
      flickity: false
    },
    methods: {
      next () {
        this.flickity.next()
      },
      prev () {
        this.flickity.previous()
      },
    },
    mounted () {
      this.flickity = new Flickity(this.$refs.items, {
        contain: true,
        wrapAround: true,
        adaptiveHeight: true,
        prevNextButtons: false,
        pageDots: false,
        selectedAttraction: 1,
        friction: 1
      })
    }
  })
};
