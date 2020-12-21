var $ = require('jquery');

var EditorialtagPage = function(el) {
  this.$el = $(el)
  this.body = document.querySelector('body')
  this.modules = document.querySelectorAll('.editorialtag-module')

  this.activity = {}
  this.pageUrl = window.location.href

  this.setModules()
  this.loaded()
}

EditorialtagPage.prototype = {

  /**
   * Set Modules
   * Loop through the editorialtag modules
   * and set a scroll listener to fire a 
   * dataLayer event when visible for the
   * first time
   */
  setModules() {
    Array.prototype.forEach.call(this.modules, module => {
      const moduleName = module.dataset.module
      this.activity[moduleName] = {
        'impression': false
      }

      window.addEventListener('scroll', () => {
        const moduleVisible = this.moduleVisible(module)
        if (moduleVisible) {
          if (!this.activity[moduleName].impression) {
            this.registerImpression(moduleName)

            this.activity[moduleName].impression = true
          }
        }
      }, false)
    })
  },

  registerImpression(moduleName) {
    dataLayer.push({
      'event': 'impression',
      'urls': this.pageUrl,
      'module': moduleName
    })
  },

  /**
   * Check if module is visible on page
   * @param {object} element 
   * @return boolean moduleVisible
   */
  moduleVisible: function(element) {
    let moduleVisible = false
    const bounding = element.getBoundingClientRect()
    const top = bounding.top >= 0
    const left = bounding.left >= 0
    const right = bounding.right <= (
      window.innerWidth || document.documentElement.clientWidth
    )
    const bottom = bounding.bottom <= (
      window.innerHeight || document.documentElement.clientHeight
    )

    if ( top && left && right && bottom) {
      moduleVisible = true
    }

    return moduleVisible
  },

  /**
   * Remove loading class that disables css transitions
   */
  loaded: function() {
    var loader = this.$el

    $(window).load(function() {
        loader.removeClass("loading");
    })
  }
}

module.exports = EditorialtagPage;
