import '@scss/gutenberg.front.scss'
var requireAll = require('./lib/require-all.js');
var initializeModules = require('./lib/init-modules.js');

//Require all components
requireAll(require.context("@modules/", true, /\.front.scss$/));
requireAll(require.context("@modules/", true, /\.front.js$/));

//Require all components - js
initializeModules(name => require( "@modules/acf/" + name + '/' + name + '.front.js' ))
initializeModules(name => require( "@modules/core/" + name + '/' + name + '.front.js' ))
initializeModules(name => require( "@modules/embed/" + name + '/' + name + '.front.js' ))
