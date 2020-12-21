var initializeModules = require('lib/init-modules.js');
var globalModules = ['image', 'parsely'];

export default () => {
  initializeModules(name => {
    if(name.startsWith("summer-")){
      return require( "@modules/summer/" + name + '/' + name + '.js' )
    } else {
      return require( "@modules/main/" + name + '/' + name + '.js' )
    }
  }, null, globalModules)
}
