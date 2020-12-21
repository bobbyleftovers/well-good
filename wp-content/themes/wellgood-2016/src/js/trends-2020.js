import '@scss/trends-2020.scss'
var initializeModules = require('./lib/init-modules.js');

initializeModules(name => require( "@modules/trends-2020/" + name + '/' + name + '.js' ), 'trends-2020')
