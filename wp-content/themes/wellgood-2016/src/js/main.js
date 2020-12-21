import '@scss/main.scss'
var initializeModules = require('./lib/init-modules.js');
var globalModules = ['image', 'parsely'];

window.WellGood = {
  jQuery
}

initializeModules(name => require( "@modules/main/" + name + '/' + name + '.js' ),'main', globalModules)

initializeModules(name => {
  switch(name){
    case 'copy-clipboard': return require( "@modules/main-2020/copy-clipboard/copy-clipboard" )
    case 'text-input': return require( "@modules/main-2020/text-input/text-input" )
  }
}, 'main-2020', globalModules)

// Fallback removal of unicode range
if(navigator.userAgent.indexOf('MSIE') >= 0 || navigator.appVersion.indexOf('Trident/') >= 0){
  jQuery('html').removeClass("unicoderange")
}
