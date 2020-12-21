import { registerVueComponent } from 'lib/init-vue'
var FontFaceObserver = require('fontfaceobserver');

var FONTS_ARE_LOADED = false;
var fontDisplay = new FontFaceObserver('orpheuspro');
var fontSerif = new FontFaceObserver('freight-text-pro');
var fontSans = new FontFaceObserver('neue-haas-unica');

Promise.all([fontDisplay.load(), fontSerif.load(), fontSans.load()]).then(function () {
  FONTS_ARE_LOADED = true;
});

module.exports = function(el){
  registerVueComponent(el, {
    props: [ 'target-class', 'properties' ],
    data: function(){
     return {
      computedStyles: {}
     }
    },
    mounted(){
      this.init();
    },
    methods: {
      init(){
        var self = this;
        if(!window.BRRL_MODULES['main-2020']){
          setTimeout(function(){
            self.init();
          }, 200)
          return;
        }
        this.getComputedStyles();
        var checkFonts = setInterval(function(){
          if(FONTS_ARE_LOADED){
            clearInterval(checkFonts);
          }
          self.getComputedStyles();
        }, 500)
        window.addEventListener('resize', function() {
          self.getComputedStyles();
        });
      },
      getComputedStyles(){
        this.computedStyles = Object.assign({}, getComputedStyle(this.$refs['styleRef']))
      }
    }
  })
}
