import '@scss/product-guide.scss'
var initializeModules = require('./lib/init-modules.js');

initializeModules(name => require( "@modules/product-guide/" + name + '/' + name + '.js' ), 'product-guide')
