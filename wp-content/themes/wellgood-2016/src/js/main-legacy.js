var initializeModules = require('./lib/init-modules.js');
var globalModules = ['image', 'parsely'];

window.WellGood = {
  jQuery
}

//Require all components - js
initializeModules(name => {
  switch(name){
    case 'header': return require( "@modules/main/header/header" )
    case 'footer': return require( "@modules/main/footer/footer" )
    case 'advertisement': return require( "@modules/main/advertisement/advertisement" )
    case 'anchor-link': return require( "@modules/main/anchor-link/anchor-link" )
    case 'signup-form': return require( "@modules/main/signup-form/signup-form" )
    case 'email-capture': return require( "@modules/main/email-capture/email-capture" )
    case 'image': return require( "@modules/main/image/image" )
  }
}, 'main', globalModules)

// Fallback removal of unicode range
if(navigator.userAgent.indexOf('MSIE') >= 0 || navigator.appVersion.indexOf('Trident/') >= 0){
  jQuery('html').removeClass("unicoderange")
}
